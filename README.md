# ig_content_blocking
Blockiert sämtliche `<iframe>` und `<script>` Elemente innerhalb des HTML body und ersetzt diese durch einen Einwilligungs-Banner.

## Requirements
- PHP 7.3
- TYPO3 10

## Wichtig
Diese Extension NICHT klonen und als Systemeigene betrachten, sondern als Git Submodule einbinden!

## Styling?
Die Extension kommt bewusst komplett ohne CSS, dieses muss im Projekt verankert werden.

## Entscheidung des Benutzers persistieren
Im `<head>` folgendes hinzugefügen:
```js
window.persistentAllowDecision = true
```
_Dies gilt auch rückwirkend für alle bisher getroffenen Entscheidungen ber Benutzer. Soll heißen: Wenn ein Benutzer gestern YouTube akzeptiert hat und heute diese Änderung gemacht wird, wird dieser Benutzer beim nächsten Aufruf nicht erneut zustimmen müssen._

## Elemente von blockiung aussließen
Füge dem Elemente das Attribut `data-no-consent-required="true"` hinzu.
