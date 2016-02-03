# Komponenty pro Nette formuláře

## Instalace

Soubory v client-side zalinkujeme do souboru.

**Nainstalujeme komponenty do formulářu**

```
class MyForms extends Nette\Application\UI\Forms {
    
    use WebChemistry\Forms\Controls\TForm;
}
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