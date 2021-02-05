<?php

namespace App\Logic\Handlers;

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
        [$limit, $offset] = self::limit($request);
        $res = emitter()->dispatch('select2.ajax:load', [$cols, $limit, $offset])->getReturnValue();
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
     * @return array
     */
    private static function limit($request)
    {
        $limit = [null, 0];

        if (isset($request['page'])) {
            $limit = [intval($request['length']), intval($request['page'])];
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