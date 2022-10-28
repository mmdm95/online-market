<?php

namespace App\Logic\Handlers;

use Sim\Utils\StringUtil;

class Select2Handler
{
    /**
     * @param $request
     * @param $columns
     * @return array
     */
    public static function handle($request, $columns): array
    {
        $cols = self::columnsNAliases($columns);
        [$where, $bindValues] = self::filter($request, $columns);
        [$limit, $offset] = self::limit($request);
        $res = emitter()->dispatch('select2.ajax:load', [$cols, $where, $bindValues, $limit, $offset])->getReturnValue();
        if (is_array($res)) {
            [$data, $recordsTotal] = $res;
        } else {
            [$data, $recordsTotal] = [[], 0];
        }

        /**
         * Output
         */
        return [
            'total_count' => $recordsTotal,
            "results" => self::dataOutput($columns, $data),
        ];
    }

    /**
     * @param $request
     * @param $columns
     * @return array
     */
    private static function filter($request, $columns): array
    {
        $globalSearch = [];
        $bindValues = [];
        $bindCounter = 0;
        $str = $request['q'] ?? '';

        if ($str != '') {
            foreach ($columns as $column) {
                // create where only on searchable columns
                if (!isset($column['searchable']) || !(bool)$column['searchable']) continue;

                // english
                $binding = 'binding' . $bindCounter++;
                $bindValues[$binding] = '%' . StringUtil::toEnglish($str) . '%';
                $phrase = $column['db'] . " LIKE :" . $binding . ' OR ';

                // persian
                $binding = 'binding' . $bindCounter++;
                $bindValues[$binding] = '%' . StringUtil::toPersian($str) . '%';
                $phrase .= $column['db'] . " LIKE :" . $binding . ' OR ';

                // arabic
                $binding = 'binding' . $bindCounter++;
                $bindValues[$binding] = '%' . StringUtil::toArabic($str) . '%';
                $phrase .= $column['db'] . " LIKE :" . $binding;

                $globalSearch[] = $phrase;
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        return [$where, $bindValues];
    }

    /**
     * @param $request
     * @return array
     */
    private static function limit($request)
    {
        $limit = [null, 0];

        if (isset($request['page'])) {
            $limit = [intval($request['length']), intval($request['length']) * (intval($request['page']) - 1)];
        }

        return $limit;
    }

    /**
     * @param $columns
     * @param $data
     * @return array
     */
    private static function dataOutput($columns, $data)
    {
        $out = [];

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = [];

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column['formatter'])) {
                    if (empty($column['db_alias'])) {
                        $row[$column['s2']] = $column['formatter']($data[$i]);
                    } else {
                        $row[$column['s2']] = $column['formatter']($data[$i][$column['db_alias']], $data[$i]);
                    }
                } else {
                    if (!empty($column['db_alias'])) {
                        $row[$column['s2']] = $data[$i][$columns[$j]['db_alias']];
                    } else {
                        $row[$column['s2']] = "";
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    /**
     * Get columns with aliases
     *
     * @param $columns
     * @return array
     */
    private static function columnsNAliases($columns)
    {
        $cols = [];

        for ($i = 0, $len = count($columns); $i < $len; $i++) {
            if (empty($columns[$i]['db'])) {
                continue;
            }
            $col = $columns[$i]['db'];
            $alias = $columns[$i]['db_alias'] ?? null;
            $cols[$i] = $col . (!empty($alias) ? " AS {$alias}" : '');
        }

        return $cols;
    }
}