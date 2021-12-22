Welcome to PowERPlay CRM V0.1.
<br /><br />
<table>
    <tr>
        <td style="vertical-align: top;">PID</td>
        <td><?php echo $_SESSION['procpid']; ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top;">PPWEBR</td>
        <td><?php echo $rep_url_pref_new."PBCommandParm=pid=".$_SESSION['procpid']."||uid=".$_SESSION['user_id']."||object=d_rep_balance_sheet_standard_1||arg1=201012||autorun=1"; ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top;">IFRAME</td>
        <td><a target="_blank" href="reports.php?url=<?php echo "object=d_rep_balance_sheet_standard_1||arg1=201012||autorun=1"; ?>">Open in iFrame</a></td>
    </tr>
</table>