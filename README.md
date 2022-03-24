# ig_content_blocking
Blockiert sämtliche `<iframe>` und `<script>` Elemente innerhalb des HTML body und ersetzt diese durch einen Einwilligungs-Banner.

## Anforderungen
- PHP < 7.4
- TYPO3 11
- Moderne Clients (**Kein** IE11 Support)

## Wichtig
Diese Extension **NICHT** klonen und als Systemeigene betrachten, sondern als Git Submodule einbinden!<br>
Als Git-URL sollte `https://pipeline:bAxb4w8YRgJWvJNcsSSs@gitlab.i-gelb.net/gdpr-resources/ig_content_blocking.git` genutzt werden.

## Styling?
Die Extension kommt bewusst ohne CSS, dieses muss im Projekt verankert werden.

## Entscheidung des Benutzers persistieren
Im `<head>` folgendes hinzugefügen:
```js
window.persistentAllowDecision = true
```
_Dies gilt auch rückwirkend für alle bisher getroffenen Entscheidungen der Benutzer:innen. Soll heißen: Wenn ein:e Benutzer:in gestern YouTube akzeptiert hat und heute diese Änderung gemacht wird, wird diese:r Benutzer:in beim nächsten Aufruf nicht erneut zustimmen müssen._

## Elemente von blockiung aussließen
Füge dem Elemente das Attribut `data-no-consent-required="true"` hinzu.
