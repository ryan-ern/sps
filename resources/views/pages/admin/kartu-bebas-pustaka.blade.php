<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10px;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .card {
            width: 350px;
            height: 310px;
            border: 1px solid black;
            padding: 10px;
            margin: 5px auto;
            box-sizing: border-box;
        }

        .header, .title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        .alamat, .website {
            font-size: 9px;
            font-style: italic;
            text-align: center;
        }

        .double-line {
            border-top: 2px double black;
            margin: 4px 0 6px;
        }

        .info {
            font-size: 11px;
            line-height: 1.4;
            margin-top: 10px;
        }

        .footer {
            text-align: right;
            font-size: 10px;
        }

        .ttd {
            margin-top: 30px;
        }

        .barcode {
            font-size: 0;
            padding-right: 10px;
        }

        table.outer {
            width: 100%;
        }

        table.inner {
            width: 100%;
        }

        td {
            vertical-align: top;
        }

        .row {
            width: 100%;
        }
    </style>
</head>
<body>
        <table class="outer">
                <tr>
                        <td>
                            <div class="card">
                                <div class="header">
                                    PEMERINTAH KABUPATEN LAMPUNG SELATAN<br>
                                    DINAS PENDIDIKAN<br>
                                    <span class="underline">UPT SMP NEGERI 1 SIDOMULYO</span>
                                </div>
                                <div class="alamat">
                                    Jl. Spontan No. 252, Sidorejo, Sidomulyo, Lampung Selatan, 35453<br>
                                    NSS. 20112021226 | NPSN. 10800513
                                </div>
                                <div class="website">
                                    www.smpn1sidomulyo.sch.id | smpn1sidomulyo@gmail.com | fb: SMPN 1 Sidomulyo LAM-SEL
                                </div>
                                <div class="double-line"></div>
                                <div class="title">
                                    KARTU BEBAS PERPUSTAKAAN<br>
                                    T.A. {{ date('Y') }}-{{ date('Y') + 1 }}
                                </div>
                                <div class="info">
                                    <table class="inner">
                                        <tr>
                                            <td width="90">Nama</td>
                                            <td>: {{ $anggota->fullname }}</td>
                                        </tr>
                                        <tr>
                                            <td>NISN/NIS</td>
                                            <td>: {{ $anggota->nisn }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kelas</td>
                                            <td>: {{ $anggota->kelas }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <table class="inner" style="margin-top: 15px;">
                                    <tr>
                                        <td class="footer">
                                            Sidomulyo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                                            Kepala Perpustakaan
                                            <div class="ttd">
                                                <strong>ASTINA, M.Pd</strong><br>
                                                Nip. 170696 200003 2 005
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                </tr>
        </table>
</body>
</html>
