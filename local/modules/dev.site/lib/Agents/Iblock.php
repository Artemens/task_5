<?php

namespace Only\Site\Agents;


class Iblock
{
public static function clearOldLogs()
    {
        global $DB;
        
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            return self::getAgentCall();
        }

        $iblockId = \Only\Site\Helpers\IBlock::getIblockID('LOG', 'SYSTEM');
        if (!$iblockId) {
            return self::getAgentCall();
        }

        $rsNewest = \CIBlockElement::GetList(
            ['ID' => 'DESC'], 
            [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y'
            ],
            false,
            ['nTopCount' => 10], 
            ['ID']
        );

        $keepIds = [];
        while ($arElement = $rsNewest->Fetch()) {
            $keepIds[] = $arElement['ID'];
        }

        if (empty($keepIds)) {
            return self::getAgentCall();
        }

        $rsAll = \CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $iblockId,
                '!ID' => $keepIds 
            ],
            false,
            false,
            ['ID']
        );

        $deletedCount = 0;
        while ($arElement = $rsAll->Fetch()) {
            if (\CIBlockElement::Delete($arElement['ID'])) {
                $deletedCount++;
            }
        }

        return self::getAgentCall();
    }
}
