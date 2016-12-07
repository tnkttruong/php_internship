<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create some HTML</title>
<style >
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
td .name{background: #99ccff;}
</style>
</head>
<body>
<table>
        <tr>
            <td class="name"style="background: #eef1f5;"><label>Tên nguyên vật liệu <span style="color: white;background-color: #de797b">Bắt buộc</span></label></td>
                <td><select style="width: 500px;height: 20px;">
                        <option value="volvo">ABCDB</option>
                        <option value="saab">CGDKKS</option>
                    </select></td>

        </tr>
        <tr>
            <td style="background: #eef1f5;"><label>Nhà cung cấp <span style="color: white;background-color: #de797b">Bắt buộc</span></label></td>
            <td><input style="width: 500px;height: 20px;" type="text" name="gread" placeholder="Nhập tên nhà cung cấp"></td>
        </tr>
        <tr>
            <td style="background: #eef1f5;"><label>Ngày nhập <span style="color: white;background-color: #de797b">Bắt buộc</span></label></td>
            <td><input style="width: 500px;height: 20px;" type="text" name="address" placeholder="nhập ngày(yyyy/mm/dd)"></td>
        </tr>
        <tr>
            <td style="background: #eef1f5;"><label>Đánh giá <span style="color: white;background-color: #de797b">Bắt buộc</span></label></td>
            <td><input type="radio" name="radio" value="1">A
                <input type="radio" name="radio" value="2">B
                <input type="radio" name="radio" value="3">C

            </td>


        </tr>
    <tr>
        <td style="background: #eef1f5;">Sách</td>
        <td><input type="checkbox" name="check1">Ngày này năm ấy</td>
    </tr>
    <tr>
        <td style="background: #eef1f5;">Truyện</td>
        <td><input type="checkbox" name="check1">Harry Potter</td>
    </tr>
    <tr>
        <td style="background: #eef1f5;">Tạp chí</td>
        <td><input type="checkbox" name="check1">Đời sống</td>
    </tr>
</table>

</body>
</html>