# Komponenty pro Nette formuláře

[![Build Status](https://travis-ci.org/WebChemistry/forms-controls.svg?branch=master)](https://travis-ci.org/WebChemistry/forms-controls)

## Instalace

Přidáme balíček webchemistry-form-controls do bower.json a nainstalujeme.
Přilinkujeme ještě tyto extenze, které se stáhnou společně s tímto balíčkem: EasyAutocomplete, jquery.tagsinput, datetimepicker, jquery.inputmask, jquery

**Nainstalujeme komponenty do formulářu**

```php
class MyForms extends Nette\Application\UI\Forms {
    
    use WebChemistry\Forms\Controls\TForm;
}
```

můžeme přidat i extension, která usnadní nastavení jednotlivých komponent

```php
extensions:
    formControls: WebChemistry\Forms\DI\FormControlsExtension
```

## Date

```php
$form->addDate('date', 'Datum');

$form->addDate('dateTwo', 'Datum')
        ->setFormat('D.M.Y');
```

## Editor

```php
$form->addEditor('editor', 'Editor');
```

## Mask

```php
$form->addMask('mask', 'Mask')
     ->setMask('+999 999 999 999');
```

## Recaptcha

```php
$form->addRecaptcha('recaptcha', 'Antispam');
```

## Suggestion

```php

class MyPresenter extends Nette\Application\UI\Presenter {
    use WebChemistry\Forms\Traits\TSuggestion;
}
```

```php
$form->addSuggestion('suggestion', 'Suggestion', function ($query) {
    // return array
});
```

## Tags

```php
$form->addTags('tags', 'Tags');
```

## Upload control

```php
$form->addPreviewUpload('upload', 'Upload', 'path/to/upload/dir');
```

## Vypnutí překladu u CheckboxList, RadioList, SelectBox, MultiSelectBox

```php
$form->addCheckboxList('checkboxList', NULL, ['myItem'])
	->setTranslate(FALSE);
```

## Client-side

Po načtení všech potřebných souborů spustíme inicializace:

```js
WebChemistry.FormsControls.init();
```

V případě, že chceme po nette.ajaxu přidělit všem novým inputům pluginy, zavoláme registrační funkci:
```js
WebChemistry.FormsControls.registerNetteAjaxEvent();
```

Pro nastavení jednotlivých komponent slouží funkce addSettings, která se musí volat před init funkcí:
```js
WebChemistry.FormsControls.addSettings({
    date: {
        enable: false
    }
});

WebChemistry.FormsControls.init();

WebChemistry.FormsControls.addSettings(); // Vyhodí v konzoli chybu, protože toto volání je zbytečné
```

Potřebujeme-li přidat novým inputům pluginy, zavoláme funkci update:
```js
WebChemistry.FormsControls.update();
```

Nové komponenty nebo přepsání stávající? Není problém:
```js
WebChemistry.FormsControls.addControl('name', {
    // Celou základní kostru máte v client/examples/control.sekeleton.js
    // Příklady nalezneta u již hotových komponent client/components/*.js
});
```

## Nastavení pro jednotlivé komponenty

Všechny možná nastavení najdete ve zdrojovém kódu komponenty ve složce client/components/*.js.
Nastavení potom probíhá velice jednoduše (př. [date input](https://github.com/WebChemistry/forms-controls/blob/master/client/components/date.js#L5) jiny selector):
jméno komponenty, naleznete na předposledním řádku (první parameter funkce addControl).

```js
WebChemistry.FormsControls.addSettings({
    date: {
        selector: 'input.myNewSelector'
    }
});
```
