
<?php
/* @var $this \yii\web\View */
/* @var $content string */

//AppAsset::register($this);
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->registerCssFile('@web/assets_b/css/pph21.css') ?>
        <?php $this->head() ?>
    </head>
    <body style="font-family: Arial;">
        <?php $this->beginBody() ?>
        <div class="" style="width: 100%;">
            <div id="page">
                <br />
                <table border="" style="width: 100%; border-collapse: collapse;border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;" cellspacing="0" class="borderAll">
                    <tr>
                        <td class="" rowspan="4" style="text-align: center; border-right: #000 solid 1px;width: 25%">
                            <?= Html::img('@web/assets_b/css/img/logo-Direktorat-Jenderal-Pajak%20-%20Copy.jpg', ['alt' => 'some', 'class' => 'thing', 'style' => 'height:80px']); ?>
                        </td>
                        <td class="" rowspan="4" style="font-size: 14px; vertical-align: text-top; text-align: center; border-right: #000 solid 1px;border-bottom: #000 solid 1px">
                            <b>BUKTI PEMOTONGAN PAJAK PENGHASILAN PASAL 21 BAGI PEGAWAI TETAP ATAU PENERIMA PENSIUN ATAU TUNJANGAN HARI TUA/JAMINAN HARI TUA BERKALA
                            </b>
                        </td>
                        <td style="font-size: 15px; text-align: center;border-bottom: #000 solid 1px" class="auto-style401" rowspan="4">&nbsp;</td>
                        <td class="" style="border-bottom: none; text-align: right;" colspan="2">
                            <?= Html::img('@web/assets_b/css/img/Untitled.jpg', ['alt' => 'some', 'class' => 'thing']); ?> </td>
                    </tr>
                    <tr>
                        <td class="auto-style403" style="font-size: 15px; text-align: right" colspan="2"><b>FORMULIR 1721-A1</b></td>
                    </tr>
                    <tr>
                        <td class="auto-style395" style="font-size: 9px; text-align: left" colspan="2">Lembar ke-1 : untuk Penerima Penghasilan
                            <br />
                            Lembar ke-2 : untuk Pemotong </td>
                    </tr>
                    <tr>
                        <td class="auto-style474" style="font-size: 11px; text-align: center; font-weight: bold;border-bottom: #000 solid 1px">&nbsp;</td>
                        <td class="auto-style402" style="font-size: 11px; text-align: center; font-weight: bold; vertical-align: bottom">MASA PEROLEHAN</td>
                    </tr>
                    <tr>
                        <td class="" style="font-size: 11px; text-align: center;border-right: #000 solid 1px" rowspan="2"><b>KEMENTRIAN KEUANGAN RI&nbsp; DIREKTORAT JENDRAL PAJAK</b></td>
                        <td class="auto-style394 borderAll" rowspan="2" colspan="3" style="font-weight: bold;border-right: #000 solid 1px">&nbsp;NOMOR&nbsp;:&nbsp;1&nbsp;.&nbsp;1&nbsp;-&nbsp;</td>
                        <td class="auto-style395" style="font-size: 11px; text-align: center; font-weight: bold; vertical-align: top">&nbsp;PENGHASILAN [mm - mm]</td>
                    </tr>
                    <tr>
                        <td class="auto-style395" style="font-size: 12px; text-align: center">&nbsp;</td>
                    </tr>
                </table>
                <br />

                <table border="" style="width: 100%; font-size: 8px; font-weight: bold;border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px; " class="borderAll" cellspacing="0">
                    <tr style="">
                        <td style="width: 1%"></td>
                        <td class=""></td>
                        <td style="width: 1%"></td>
                        <td class=""></td>
                        <td></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="width: 1%"></td>
                    </tr>
                    <tr >
                        <td style="width: 1%"></td>
                        <td style="width: 8%">NPWP </td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 1%"></td>
                        <td style="">PEMOTONG</td>
                        <td style="">:</td>
                        <td style="width: 20%;border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td style="width: 1%">-</td>
                        <td style="width: 8%;border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td style="width: 1%;">.</td>
                        <td style="width: 8%;border-bottom: #000 solid 1px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 1%"></td>
                        <td class="">&nbsp;</td>
                        <td class="">&nbsp;</td>
                        <td class="">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 1%"></td>
                        <td class="">NAMA </td>
                        <td class="">&nbsp;</td>
                        <td class=""></td>
                        <td class="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 1%"></td>
                        <td class="">PEMOTONG</td>
                        <td class="">:</td>
                        <td style="border-bottom: #000 solid 1px;" colspan="6">&nbsp;</td>
                    </tr>
                    <tr style="">
                        <td style="width: 1%"></td>
                        <td class="">&nbsp;</td>
                        <td class=""></td>
                        <td class=""></td>
                        <td></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                    </tr>
                </table>

                <br />

                <table style="width: 100%;font-size: 10px;">
                    <tr>
                        <td><b>A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG</b></td>
                    </tr>
                </table>

                <table border="" style="width: 100%; font-size: 8px; font-weight: bold;border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;" class="borderAll" cellspacing="0">
                    <tr>
                        <td class="" style="width: 3%; text-align: center">1</td>
                        <td class="" style="width: 10%;">2</td>
                        <td class="" style="width: 1%;">3</td>
                        <td class="" style="width: 3%;">4</td>
                        <td class="" style="width: 15%;">5</td>
                        <td class="" style="width: 3%;">6</td>
                        <td class="" style="width: 8%;">7</td>
                        <td class="" style="width: 3%;">8</td>
                        <td class="" style="width: 8%;">9</td>
                        <td class="" style="width: 3%;">10</td>
                        <td class="" style="width: 3%;">11</td>
                        <td class="" style="width: 2.3%;">12</td>
                        <td class="" style="width: 3%;">13</td>
                        <td class="" style="width: 2.4%;">14</td>
                        <td class="" style="width: 3%;">15</td>
                        <td class="" style="width: 2.6%;">16</td>
                        <td class="" style="width: 3%;">17</td>                       
                        <td class="" style="width: 4%;">18</td>
                        <td style="" style="width: 15%;">19</td>
                        <td style="">20</td>
                    </tr>

                    <tr>
                        <td class="" style="text-align:center;">1.</td>
                        <td height="20"  class="">NPWP&nbsp;</td>
                        <td class="" >:</td>
                        <td colspan="2" style="border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td style="" style="text-align:center;">-</td>
                        <td style="border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td style="" style="text-align:center;">.</td>
                        <td style="border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td class="">&nbsp;</td>
                        <td class="">6.</td>
                        <td class="" colspan="8">STATUS / JUMLAH TANGGUNGAN UNTUK PTKP</td>
                        <td style=""></td>
                    </tr>
                    <tr>
                        <td class="" style="text-align:center;">2.</td>
                        <td class="">NIK . NO</td>
                        <td class=""></td>
                        <td class="" colspan="6"></td>
                        <td class="">&nbsp;</td>
                        <td class="">&nbsp;</td>
                        <td class="" style="">K/</td>
                        <td class="" style="border-bottom: #000 solid 1px;"></td>
                        <td class="" style="">TK/</td>
                        <td class="" style="border-bottom: #000 solid 1px;"></td>
                        <td class="" style="">HB/</td>
                        <td class="" style="border-bottom: #000 solid 1px;" ></td>
                        <td class="" style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                    </tr>
                    <tr>
                        <td class=""></td>
                        <td class="">PASSPOR</td>
                        <td class="">:</td>
                        <td class="" colspan="6" style="border-bottom: #000 solid 1px;"></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                    </tr>
                    <tr>
                        <td class="" style="text-align:center;">3.</td>
                        <td class="">NAMA</td>
                        <td class="">:</td>
                        <td height="20"  class="" colspan="6" style="border-bottom: #000 solid 1px;"></td>
                        <td class=""></td>
                        <td class="">7.</td>
                        <td class="" colspan="4">NAMA JABATAN :</td>
                        <td style="border-bottom:#000 solid 1px;" class="" colspan="4"></td>
                        <td style=""></td>
                    </tr>

                    <tr>
                        <td height="10" class="" colspan="20"></td>
                    </tr>

                    <tr>
                        <td class="" style="text-align:center;">4.</td>
                        <td class="">ALAMAT</td>
                        <td class="">:</td>
                        <td height="20"  style="border-bottom: #000 solid 1px;" colspan="6"></td>
                        <td class=""></td>
                        <td class="">8.</td>
                        <td class="" colspan="5">KARYAWAN ASING:</td>
                        <td class="" colspan="1" style="border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td class="">&nbsp;YA</td>
                        <td style="" colspan="1"></td>
                        <td style=""></td>
                    </tr>

                    <tr>
                        <td class="">&nbsp;</td>
                        <td class="">&nbsp;</td>
                        <td height="20" class="">&nbsp;</td>
                        <td height="30" style="border-bottom: #000 solid 1px;" colspan="6">&nbsp;</td>
                        <td class=""></td>
                        <td style="vertical-align: bottom">9.</td>
                        <td style="vertical-align: bottom" colspan="6">KODE NEGARA DOMISILI:</td>
                        <td class="auto-style547" style="border-bottom: #000 solid 1px;" colspan="2">&nbsp;</td>
                        <td style=""></td>
                    </tr>

                    <tr>
                        <td height="10" class="" colspan="20"></td>
                    </tr>

                    <tr>
                        <td class="" style="text-align:center;">5.</td>
                        <td class="" height="20" >JENIS KELAMIN</td>
                        <td class="">:</td>
                        <td class="" style="border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td class=""> &nbsp; Laki Laki</td>
                        <td class="" style="border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td class="" colspan="3" >&nbsp;Perempuan</td>
                        <td style=""></td>
                        <td class="auto-style460" colspan="10">&nbsp;</td>
                    </tr>

                    <tr>
                        <td height="10" class="" colspan="20"></td>
                    </tr>
                </table>

                <br />

                <table style="width: 100%;font-size: 10px;">
                    <tr>
                        <td class=""><b>B. RINCIAN PENGHASILAN DAN PENGHITUNGAN PPh PASAL 21</b></td>
                    </tr>
                </table>

                <table  style="width: 100%; font-size: 10px; height: 488px; border-collapse: collapse;" border="1" bordercolor="BLACK" cellspacing="0">
                    <tr>
                        <td class="auto-style449" colspan="2" style="font-size: 12px">
                    <center><b>URAIAN</b></center>
                    </td>
                    <td class="auto-style448" style="font-size: 12px">
                    <center><b>JUMLAH (Rp)</b></center>
                    </td>
                    </tr>
                    <tr>
                        <td class="auto-style452" colspan="2">KODE OBJEK PAJAK:21-100-0121-100-02</td>
                        <td class="" style="background-color: grey"></td>
                    </tr>
                    <tr>
                        <td class="auto-style452" colspan="2"><b>PENGHASILAN BRUTO :</b></td>
                        <td class="auto-style172" style="vertical-align: middle; background-color: grey"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">1.</td>
                        <td class="auto-style454">GAJI/PENSIUN ATAU THT/JHT</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">2.</td>
                        <td class="auto-style454">TUNJANGAN PPh</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">3.</td>
                        <td class="auto-style454">TUNJANGAN LAINNYA, UANG LEMBUR DAN SEBAGAINYA</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">4.</td>
                        <td class="auto-style454">HONORARIUM DAN IMBALAN LAIN SEJENISNYA</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">5.</td>
                        <td class="auto-style454">PREMI ASURANSI YANG DIBAYAR PEMBERI KERJA</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">6.</td>
                        <td class="auto-style454">PENERIMAAN DALAM BENTUK NATURA DAN KENIKMATAN LAINNYA YANG DIKENAKAN PEMOTONGAN PPh PASAL 21</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">7.</td>
                        <td class="auto-style454">TANTIEM, BONUS, GRATIFIKASI, JASA PRODUKSI DAN THR</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">8.</td>
                        <td class="auto-style454">JUMLAH PENGHASILAN BRUTO (1 S.D.7)</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style452" colspan="2"><b>PENGURANGAN :</b></td>
                        <td class="auto-style172" style="background-color: grey"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">9.</td>
                        <td class="auto-style454">BIAYA JABATAN/ BIAYA PENSIUN</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">10.</td>
                        <td class="auto-style454">IURAN PENSIUN ATAU IURAN THT/JHT</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">11.</td>
                        <td class="auto-style454">JUMLAH PENGURANGAN (9 S.D 10)</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style103" colspan="2"><b>PENGHITUNGAN PPh PASAL 21 :</b></td>
                        <td class="auto-style172" style="background-color: grey"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">12.</td>
                        <td class="auto-style454">JUMLAH PENGHASILAN NETO (8-11)</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">13.</td>
                        <td class="auto-style454">PENGHASILAN NETO MASA SEBELUMNYA</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">14.</td>
                        <td class="auto-style454">JUMLAH PENGHASILAN NETO UNTUK PENGHITUNGAN PPh PASAL 21 (SETAHUN/DISETAHUNKAN)</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">15.</td>
                        <td class="auto-style454">PENGHASILAN TIDAK KENA PAJAK (PTKP)</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">16.</td>
                        <td class="auto-style454">PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN (14 - 15)</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">17.</td>
                        <td class="auto-style454">PPh PASAL 21 ATAS PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">18.</td>
                        <td class="auto-style454">PPh PASAL 21 YANG TELAH DIPOTONG MASA SEBELUMNYA</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style455">19.</td>
                        <td class="auto-style454">PPh PASAL 21 TERUTANG</td>
                        <td class="auto-style172"></td>
                    </tr>
                    <tr>
                        <td class="auto-style456">20.</td>
                        <td class="auto-style348">PPh PASAL 21 DAN PPh PASAL 26 YANG TELAH DIPOTONG DAN DILUNASI</td>
                        <td class="auto-style172"></td>
                    </tr>
                </table>

                <br />

                <table style="width: 100%;font-size: 10px;">
                    <tr>
                        <td><b>C. IDENTITAS PEMOTONG</b></td>
                    </tr>
                </table>

                <table border="" style="border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;width: 100%; font-size: 10px" cellspacing="0">
                    <tr>
                        <td style="width: 3%;"></td>
                        <td style="width: 6%;"></td>
                        <td style="width: 1%;"></td>
                        <td style="width: 12%;"></td>
                        <td style="width: 12%;"></td>
                        <td style="width: 3%;"></td>
                        <td style="width: 8%;"></td>
                        <td style="width: 3%;"></td>
                        <td style="width: 7%;"></td>
                        <td style="width: 3%;"></td>
                        <td style="width: 5%;"></td>
                        <td style="width: 1%;"></td>
                        <td style="width: 5%;"></td>
                        <td style="width: 1%;"></td>
                        <td style="width: 7%;"></td>
                        <td style="width: 1%;"></td>
                        <td style=""></td>
                        <td style="width: 0.5%;"></td>
                    </tr>
                    <tr>
                        <td height="20" class="" style="text-align: center">1</td>
                        <td class="">NPWP</td>
                        <td class="">:</td>
                        <td colspan="2" style="border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td style="" style="text-align:center;">-</td>
                        <td style="border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td style="" style="text-align:center;">.</td>
                        <td style="border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td class="">&nbsp;</td>
                        <td class="" style="" colspan="5">3.&nbsp;Tanggal Tanda Tangan</td>
                        <td class="" style="">&nbsp;</td>
                        <td class="" rowspan="2" style="border-right: #000 solid 1px;border-left: #000 solid 1px; border-top: #000 solid 1px; border-bottom: #000 solid 1px;">&nbsp;</td>
                        <td class="" style=""></td>
                    </tr>
                    <tr>
                        <td height="20" class="" style="text-align: center">2</td>
                        <td class="" style="">NAMA</td>
                        <td class="" style="">:</td>
                        <td class="" style="border-bottom: #000 solid 1px;" colspan="6"></td>
                        <td style=""></td>
                        <td style="border-bottom: #000 solid 1px;"></td>
                        <td class="">-</td>
                        <td style="border-bottom: #000 solid 1px;"></td>
                        <td style="">-</td>
                        <td style="border-bottom: #000 solid 1px;"></td>
                        <td style=""></td>
                        <td style=""></td>
                    </tr>
                    <tr>
                        <td class="" colspan="18"></td>
                    </tr>
                    <tr>
                        <td class="" colspan="18"></td>
                    </tr>
                </table>
            </div>
            <?php $this->endBody() ?>
        </div>
    </body>
</html>
<?php $this->endPage() ?>