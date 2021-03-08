<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Счет на оплату #{{ $data->id ?? ''}}</title>
    <style type="text/css">
        * {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            line-height: 14px;
        }
        table {
            margin: 0 0 15px 0;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }
        table td {
            padding: 5px;
        }
        table th {
            padding: 5px;
            font-weight: bold;
        }

        .header {
            margin: 0 0 0 0;
            padding: 0 0 15px 0;
            font-size: 12px;
            line-height: 12px;
            text-align: center;
        }

        /* Реквизиты банка */
        .details td {
            padding: 3px 2px;
            border: 1px solid #000000;
            font-size: 12px;
            line-height: 12px;
            vertical-align: top;
        }

        h1 {
            margin: 0 0 10px 0;
            padding: 10px 0 10px 0;
            border-bottom: 2px solid #000;
            font-weight: bold;
            font-size: 20px;
        }

        /* Поставщик/Покупатель */
        .contract th {
            padding: 3px 0;
            vertical-align: top;
            text-align: left;
            font-size: 13px;
            line-height: 15px;
        }
        .contract td {
            padding: 3px 0;
        }

        /* Наименование товара, работ, услуг */
        .list thead, .list tbody  {
            border: 2px solid #000;
        }
        .list thead th {
            padding: 4px 0;
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
        }
        .list tbody td {
            padding: 3px 3px;
            border: 1px solid #000;
            vertical-align: middle;
            font-size: 11px;
            line-height: 13px;
        }
        .list tfoot th {
            padding: 3px 2px;
            border: none;
            text-align: right;
        }

        /* Сумма */
        .total p {
            margin: 0;
            padding: 0;
        }

        /* Руководитель, бухгалтер */
        .sign {
            position: relative;
        }
        .sign table {
            width: 60%;
        }
        .sign th {
            padding: 40px 0 0 0;
            text-align: left;
        }
        .sign td {
            padding: 40px 0 0 0;
            border-bottom: 1px solid #000;
            text-align: right;
            font-size: 12px;
        }

        .sign-1 {
            position: absolute;
            left: 149px;
            top: -44px;
        }
        .printing {
            position: absolute;
            left: 271px;
            top: -15px;
        }
    </style>
</head>
<body>
<p class="header">
    Внимание! Оплата данного счета означает согласие с условиями
</p>

<table class="details">
    <tbody>
    <tr>
        <td colspan="2" style="border-bottom: none;">{{ $settingBank['beneficiary_bank'] }}</td>
        <td>БИК</td>
        <td style="border-bottom: none;">{{ $settingBank['bic'] }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border-top: none; font-size: 10px;">Банк получателя</td>
        <td>Р/сч. №</td>
        <td style="border-top: none;">{{ $settingBank['r_account'] }}</td>
    </tr>
    <tr>
        <td width="25%">ИНН {{ $settingBank['inn'] }}</td>
        <td width="30%">КПП {{ $settingBank['kpp'] }}</td>
        <td width="10%" rowspan="3">К/сч. №</td>
        <td width="35%" rowspan="3">{{ $settingBank['k_account'] }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border-bottom: none;">{{ $settingBank['name'] }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border-top: none; font-size: 10px;">Получатель</td>
    </tr>
    </tbody>
</table>

<h1>Счет на оплату № И-{{ $payments['shopping_cart_id'] }} от {{ \Carbon\Carbon::parse($payments['updated_at'])->isoFormat("DD MMMM YYYY") }}</h1>

<table class="contract">
    <tbody>
    <tr>
        <td width="15%">Поставщик:</td>
        <th width="85%">
            {{ $settingBank['name'] }}, ИНН {{ $settingBank['inn'] }}, КПП {{ $settingBank['kpp'] }}, {{ $settingBank['address'] }}
        </th>
    </tr>
    <tr>
        <td>Покупатель:</td>
        <th>
            {{ $payments['company'] }}, ИНН {{ $payments['inn'] }}, КПП {{ $payments['kpp'] }}, {{ $payments['postal_code'] }}, {{ $payments['legal_address'] }}, {{ $payments['phone'] }}, {{ $payments['email'] }}, {{ $payments['name'] }}
        </th>
    </tr>
    </tbody>
</table>

<table class="list">
    <thead>
    <tr>
        <th width="5%">№</th>
        <th width="80%">Наименование товара</th>
        <th width="15%">Цена</th>
    </tr>
    </thead>
    <tbody>

    @php($total = 0)
    @foreach($products as $product)
    <tr>
        <td align="center">{{ $loop->iteration }}</td>
        <td align="left">{{ $product->research->title }}</td>
        <td align="right">{{ $product->research->price }} руб</td>
    </tr>
    @php($total = $total + $product->research->price)
    @endforeach

    </tbody>
    <tfoot>
    <tr>
        <th colspan="2">Всего к оплате:</th>
        <th>{{ $total }}</th>
    </tr>

    </tfoot>
</table>

<div class="sign">
    <img class="sign-1" src="">
    <img class="printing" src="">

    <table>
        <tbody>
        <tr>
            <th width="30%">{{ $settingBank['name_signatory'] }}</th>
            <td>{{ $settingBank['position_signatory'] }}</td>
        </tr>

        </tbody>
    </table>
</div>
</body>
</html>