<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create some HTML</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 60%;
        }

        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 15px;
        }

        .span {
            color: white;
            background-color: #de797b;
        }

        .color {
            background: #eef1f5;
        }

        .input {
            width: 500px;
            height: 20px;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <td class="color"><label>Tên nguyên vật liệu <span
                        class="span">Bắt buộc</span></label></td>
        <td><select class="input">
                <option value="volvo">ABCDB</option>
                <option value="saab">CGDKKS</option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="color"><label>Nhà cung cấp <span class="span">Bắt buộc</span></label>
        </td>
        <td><input class="input" type="text" name="gread" placeholder="Nhập tên nhà cung cấp"></td>
    </tr>
    <tr>
        <td class="color"><label>Ngày nhập <span
                        class="span">Bắt buộc</span></label></td>
        <td><input class="input" type="text" name="address" placeholder="nhập ngày(yyyy/mm/dd)">
        </td>
    </tr>
    <tr>
        <td class="color"><label>Đánh giá <span class="span">Bắt buộc</span></label></td>
        <td><input type="radio" name="radio" value="1">A
            <input type="radio" name="radio" value="2">B
            <input type="radio" name="radio" value="3">C
        </td>
    </tr>
    <tr>
        <td class="color">Sách</td>
        <td><input type="checkbox" name="book">Ngày này năm ấy</td>
    </tr>
    <tr>
        <td class="color">Truyện</td>
        <td><input type="checkbox" name="story">Harry Potter</td>
    </tr>
    <tr>
        <td class="color">Tạp chí</td>
        <td><input type="checkbox" name="magazine">Đời sống</td>
    </tr>
</table>
</body>
</html>