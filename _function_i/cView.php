<?php
class cView
{
    function vViewData($sSql)
    {
        $data = [];
        $query = mysqli_query($GLOBALS["conn"], $sSql);
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
        mysqli_close($conn);
    }
}
