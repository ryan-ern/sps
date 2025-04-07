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
            height: 280px;
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
            text-align: center;
            vertical-align: middle;
            width: 50%;
        }


        table.outer {
            width: 100%;
            page-break-after: always;
            margin-top: 50px;
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
    @foreach($anggota->chunk(6) as $chunk)
        <table class="outer">
            @foreach($chunk->chunk(2) as $row)
                <tr>
                    @foreach($row as $item)
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
                                    KARTU ANGGOTA PERPUSTAKAAN<br>
                                    T.A. {{ date('Y') }}-{{ date('Y') + 1 }}
                                </div>
                                <div class="info">
                                    <table class="inner">
                                        <tr>
                                            <td width="90">Nama</td>
                                            <td>: {{ $item->fullname }}</td>
                                        </tr>
                                        <tr>
                                            <td>NISN/NIS</td>
                                            <td>: {{ $item->nisn }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kelas</td>
                                            <td>: {{ $item->kelas }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <table class="inner" style="margin-top: 15px;">
                                    <tr>
                                        <td class="barcode">
                                            {!! DNS1D::getBarcodeHTML($item->nisn, 'C128', 2.5, 35) !!}
                                        </td>
                                        <td class="footer">
                                            Sidomulyo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                                            Kepala Perpustakaan
                                            <div class="ttd">
                                                <strong>ASTINA, M.Pd</strong><br>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    @endforeach
                    @if($row->count() < 2)
                        <td></td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endforeach
</body>
</html>
