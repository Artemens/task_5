<?php

namespace Only\Site\Handlers;


class Iblock
{
   public static function addLog(&$arFields)
    {
        $logIblock = \CIBlock::GetList([], ['CODE' => 'LOG', 'CHECK_PERMISSIONS' => 'N'])->Fetch();
        if (!$logIblock || $arFields['IBLOCK_ID'] == $logIblock['ID']) {
            return;
        }

        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            return;
        }

        $iblock = \CIBlock::GetByID($arFields['IBLOCK_ID'])->Fetch();
        $element = \CIBlockElement::GetByID($arFields['ID'])->Fetch();
        if (!$iblock || !$element) {
            return;
        }

        $section = \CIBlockSection::GetList(
            [],
            ['IBLOCK_ID' => $logIblock['ID'], 'CODE' => 'IBLOCK_'.$iblock['ID']],
            false,
            ['ID']
        )->Fetch();

        if (!$section) {
            $bs = new \CIBlockSection;
            $sectionId = $bs->Add([
                'IBLOCK_ID' => $logIblock['ID'],
                'NAME' => $iblock['NAME'],
                'CODE' => 'IBLOCK_'.$iblock['ID'],
                'ACTIVE' => 'Y'
            ]);
        } else {
            $sectionId = $section['ID'];
        }

        $path = [];
        if ($element['IBLOCK_SECTION_ID']) {
            $nav = \CIBlockSection::GetNavChain($arFields['IBLOCK_ID'], $element['IBLOCK_SECTION_ID']);
            while ($s = $nav->Fetch()) {
                $path[] = $s['NAME'];
            }
        }

        $el = new \CIBlockElement;
        $el->Add([
            'IBLOCK_ID' => $logIblock['ID'],
            'IBLOCK_SECTION_ID' => $sectionId,
            'NAME' => $arFields['ID'],
            'ACTIVE_FROM' => $element['TIMESTAMP_X'] ?: $element['DATE_CREATE'],
            'PREVIEW_TEXT' => implode(' -> ', array_filter([
                $iblock['NAME'],
                implode(' -> ', $path),
                $element['NAME']
            ])),
            'PROPERTY_VALUES' => [
                'ELEMENT_ID' => $arFields['ID'],
                'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                'OPERATION_TYPE' => isset($arFields['RESULT']) ? 'UPDATE' : 'ADD',
                'USER_ID' => $GLOBALS['USER']->GetID()
            ]
        ]);
    }
}

?>