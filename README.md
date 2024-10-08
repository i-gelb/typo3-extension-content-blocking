# ig_content_blocking
Blockiert sämtliche `<iframe>` und `<script>` Elemente innerhalb des HTML body und ersetzt diese durch einen Einwilligungs-Banner.

## Anforderungen
- PHP < 7.4
- TYPO3 11
- Moderne Clients (**Kein** IE11 Support)

## Styling?
Die Extension kommt bewusst ohne CSS, dieses muss im Projekt verankert werden.

## Elemente von blockiung aussließen
Füge dem Elemente das Attribut `data-no-consent-required="true"` hinzu.

## Einbindung via Composer
Token: `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`
