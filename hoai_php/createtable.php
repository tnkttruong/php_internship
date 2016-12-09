<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 70%;
        }

        td, th {
            border: 1px solid #e8ecf1;
            text-align: left;
            padding: 15px;
        }

        tr:nth-child(even) {
            background-color: #fbfcfd;
        }

        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 14px;
            text-align: center;
            font-size: 13px;
            margin: 4px 2px;
        }

        .add {
            background-color: #5dc1cf;
        }

        .delete {
            background-color: #489ad9;
        }

        .update {
            background-color: #d86362;
        }

        .span {
            color: white;
        }

        .purple {
            background-color: #847ca8;
        }

        .red {
            background-color: #d55450;
        }

        .blue {
            background-color: #4f7bbc;
        }

        .gray {
            background-color: #bbc3cf;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <th>Tên nguyên liệu</th>
        <th>ID</th>
        <th>Nhà cung cấp</th>
        <th>Ngày nhập</th>
        <td></td>
    </tr>
    <tr>
        <td><span class="span purple">Cà phê chuyên biệt</span></td>
        <td>01</td>
        <td><span class="span gray">Cà phê Trung Nguyên</span></td>
        <td>2016/12/3</td>
        <td>
            <button class="button add">add</button>
            <button class="button delete">delete</button>
            <button class="button update">update</button>
        </td>
    </tr>
    <tr>
        <td><span class="span red">Cà phê Rang xay</span></td>
        <td>02</td>
        <td><span class="span blue">Cà Phê G7</span></td>
        <td>2016/3/2</td>
        <td>
            <button class="button add">add</button>
            <button class="button delete">delete</button>
            <button class="button update">update</button>
        </td>
    </tr>
    <tr>
        <td><span class="span red">Cà phê Culi</span></td>
        <td>03</td>
        <td><span class="span gray">Cà phê Trung Nguyên</span></td>
        <td>2016/3/2</td>
        <td>
            <button class="button add">add</button>
            <button class="button delete">delete</button>
            <button class="button update">update</button>
        </td>
    </tr>
    <tr>
        <td><span class="span blue">Cà phê Espresso</span></td>
        <td>04</td>
        <td><span class="span gray">Cà phê Trung Nguyên</span></td>
        <td>2016/3/2</td>
        <td>
            <button class="button add">add</button>
            <button class="button delete">delete</button>
            <button class="button update">update</button>
        </td>
    </tr>
    <tr>
        <td><span class="span blue">Cà phê Hòa tan</span></td>
        <td>05</td>
        <td><span class="span blue">Cà Phê G7</span></td>
        <td>2016/3/2</td>
        <td>
            <button class="button add">add</button>
            <button class="button delete">delete</button>
            <button class="button update">update</button>
        </td>
    </tr>
</table>
</body>
</html>