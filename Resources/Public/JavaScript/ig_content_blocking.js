window.persistentAllowDecision = true;

const allowContentElementButtons = document.querySelectorAll('.cc-blocked button')
const blockedContentElements = document.querySelectorAll('.cc-blocked')
const removeDomainFromAllowListButton = document.querySelectorAll('.external-consent-management__button')

/**
 * Allows a new hostname
 *
 * @param {string} hostname The hostname
 */
window.addHostToAllowlist = (hostname) => {
  let hostnames = getAllowedHosts()

  if (!hostnames.includes(hostname)) {
    hostnames.push(hostname)
  }

  persistHostnamesInCookie(hostnames)

  document.dispatchEvent(
    new CustomEvent("hostname_permitted", { hostname })
  )
}

/**
 * Removes a hostname from the allowlist
 *
 * @param {string} hostname The hostname
 */
window.removeHostFromAllowlist = (hostname) => {
  let hostnames = getAllowedHosts()

  hostnames = hostnames.filter((value, index, arr) => {
    if (value !== hostname) {
      return value
    }
  })

  window.persistHostnamesInCookie(hostnames)

  document.dispatchEvent(
    new CustomEvent("hostname_denied", { hostname })
  )
}

/**
 * Returns the allowed hostnames
 *
 * @returns {array} The allowed hostnames
 */
window.getAllowedHosts = () => {
  const cookies = document.cookie.split('; ')
  let allowedHostnames = []

  cookies.forEach(cookie => {
    const name = cookie.split('=')[0]
    const value = cookie.split('=')[1]

    if (name === 'allowed_domains') {
      allowedHostnames = JSON.parse(value)
    }
  })

  return allowedHostnames
}

/**
 * Writes a cookie with the given hostnames
 *
 * @param {array} hostnames
 */
window.persistHostnamesInCookie = (hostnames) => {
  const date = new Date()
  date.setTime(date.getTime() + 31536000000) // One year
  document.cookie = `allowed_domains=${JSON.stringify(hostnames)}; expires=${date.toGMTString()}; path=/`
}

if (allowContentElementButtons && blockedContentElements) {
  allowContentElementButtons.forEach(element => {
    element.addEventListener('click', event => {
      // Add original attributes to new element
      const tagName = event.target.parentElement.parentElement.getAttribute('data-node-name')
      const attributes = event.target.parentElement.parentElement.attributes
      const element = document.createElement(tagName)

      for (let i = 0; i < attributes.length; i++) {
        element.setAttribute(
          attributes[i].nodeName.replace('data-attribute-', ''),
          attributes[i].nodeValue
        )
      }

      event.target.parentElement.parentElement.replaceWith(element)

      // Allow all other elements with that domain
      if (event.isTrusted) {
        const hostname = event.target.parentElement.parentElement.getAttribute('data-hostname')
        window.addHostToAllowlist(hostname)

        blockedContentElements.forEach(element => {
          const host = element.getAttribute('data-hostname')
          const allowButton = element.querySelector('button')

          // Restore css for iframe styling
          if (element.parentElement) {
            if (element.parentElement.classList.contains('video-embed')) {
              element.parentElement.classList.remove('blocked')
            }
          }

          if (host === hostname) {
            allowButton.click()
          }
        })
      }

      document.dispatchEvent(
        new CustomEvent("content_unblocked")
      )
    })
  })

  const allowedHostnames = window.getAllowedHosts()
  blockedContentElements.forEach(element => {
    // Enable all previously allowed elements
    if (window.persistentAllowDecision) {
      const hostname = element.getAttribute('data-hostname')

      if (allowedHostnames.includes(hostname)) {
        element.querySelector('button').click()
      }
    }

    // Add `blocked` class to parent element for styling
    if (element.parentElement) {
      if (element.parentElement.classList.contains('video-embed')) {
        element.parentElement.classList.add('blocked')
      }
    }
  })
}

if (removeDomainFromAllowListButton) {
  removeDomainFromAllowListButton.forEach(element => {
    element.addEventListener('click', event => {
      if (event.isTrusted) {
        window.removeHostFromAllowlist(event.target.getAttribute('data-domain'))

        window.location.reload();
      }
    })
  })
}
