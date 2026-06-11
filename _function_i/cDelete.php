<?php
class cDelete
{
    function _dDeleteData($field, $value, $table)
    {
        if (
            is_string($value) &&
            preg_match('/^\'(.*)\'$/s', $value, $matches)
        ) {
            $escaped_value = mysqli_real_escape_string(
                $GLOBALS["conn"],
                $matches[1],
            );
            $sqldel =
                "DELETE FROM " .
                $table .
                " WHERE " .
                $field .
                " = '" .
                $escaped_value .
                "'";
        } elseif (is_numeric($value)) {
            $sqldel =
                "DELETE FROM " . $table . " WHERE " . $field . " = " . $value;
        } else {
            $escaped_value = mysqli_real_escape_string(
                $GLOBALS["conn"],
                $value,
            );
            $sqldel =
                "DELETE FROM " .
                $table .
                " WHERE " .
                $field .
                " = '" .
                $escaped_value .
                "'";
        }
        $query = mysqli_query($GLOBALS["conn"], $sqldel);

        if ($query) {
            echo "<script>
					Swal.fire({
					  position:'center',
					  width:'20em',
					  icon:'success',
					  text: 'Data berhasil dihapus',
					  type: 'error',
					}).then(function (result) {
					  if (true) {
					    window.location = '';
					  }
			}) </script>";
        } else {
            echo "<script>
					Swal.fire({
					  position:'center',
					  width:'20em',
					  icon: 'error',	
					  text: 'Data tidak berhasil dihapus',
					  type: 'error',
					}).then(function (result) {
					  if (true) {
					    window.location = '';
					  }
			}) </script>";
        }
    }

    function _dDeleteDataTrial($field, $value, $table)
    {
        if (
            is_string($value) &&
            preg_match('/^\'(.*)\'$/s', $value, $matches)
        ) {
            $escaped_value = mysqli_real_escape_string(
                $GLOBALS["conn"],
                $matches[1],
            );
            $sqldel =
                "DELETE FROM " .
                $table .
                " WHERE " .
                $field .
                " = '" .
                $escaped_value .
                "'";
        } elseif (is_numeric($value)) {
            $sqldel =
                "DELETE FROM " . $table . " WHERE " . $field . " = " . $value;
        } else {
            $escaped_value = mysqli_real_escape_string(
                $GLOBALS["conn"],
                $value,
            );
            $sqldel =
                "DELETE FROM " .
                $table .
                " WHERE " .
                $field .
                " = '" .
                $escaped_value .
                "'";
        }
        echo "<p>" . $sqldel . "</p>";
    }
}
