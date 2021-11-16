<!DOCTYPE html>
<html>
<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body, html {height: 100%}
    .bgimg {
        background-image: url('https://www.w3schools.com/w3images/onepage_restaurant.jpg');
        min-height: 100%;
        background-position: center;
        background-size: cover;
    }
</style>
<body class="bgimg w3-text-white">
<h3 style="text-align:center;">Manage City</h3>
<!-- Content -->
<div class="w3-container w3-padding-64">
    <div id="error" class="w3-pink"></div>
    <form action="/AssignmentDW/processStreet.php" method="post" name="formCity">
        <span class="w3-text-red w3-white errorName"></span>
        <input class="w3-input w3-padding-16" type="text" name="name" placeholder="Name Street..">


        <select class="w3-input w3-padding-16" name="id_district" id="load_district">

        </select>

        <span class="w3-text-red w3-white errorCreatedAt"></span>
        <input class="w3-input w3-padding-16" type="date" name="created_at" placeholder="Created At..">

        <span class="w3-text-red w3-white errorDes"></span>
        <input class="w3-input w3-padding-16" type="text" name="des" placeholder="Description">

        <select class="w3-input w3-padding-16" name="status">
            <option value="0">Đang sử dụng</option>
            <option value="1">Đang thi công</option>
            <option value="2">Đang tu sửa</option>
        </select>
        <div class="w3-margin-top">
            <input type="submit" value="Submit">
            <button type="reset" id="reload">Refesh</button>
            <button type="button" id="listCity">List</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        const inputName = $('input[name=name]');
        const inputDistrict = $('select[name=id_district]');
        const inputCreatedAt = $('input[name=created_at]');
        const inputDes = $('input[name=des]');
        const selectStatus = $('select[name=status]');
        const errorElement = $('#error');

        $('form[name=formCity]').submit(function (event) {
            let message = [];
            errorElement.html("");
            if (inputName.val() === '' || inputName.val() == null){
                message.push('Name Street is required\n');
                $('.errorName').text("Name Street is required");
            } else {
                $('.errorName').text("");
            }
            if (inputDes.val() === '' || inputDes.val() == null){
                message.push('Des is required\n');
                $('.errorDes').text("Des is required");
            } else {
                $('.errorDes').text("");
            }
            if (inputCreatedAt.val() === '' || inputCreatedAt.val() == null){
                message.push('Created at is required\n');
                $('.errorCreatedAt').text("Created at is required");
            } else {
                $('.errorCreatedAt').text("");
            }
            if (message.length > 0){
                for (let i = 0; i < message.length; i++) {
                    errorElement.append("<div>"+ message[i] +"</div>");
                }
                // alert(message);
                // errorElement.text(message.join(', '));
                event.preventDefault();
                return false;
            }
            event.preventDefault(); // đảm bảo dữ liệu sẽ gửi đi nhưng sẽ không chạy đến đường dẫn, tức là ở nguyên tại chỗ
            let data = {
                name: inputName.val(),
                id_district: inputDistrict.val(),
                created_at: inputCreatedAt.val(),
                des: inputDes.val(),
                status: selectStatus.val(),
            };
            // alert(JSON.stringify(data));

            $.ajax({
                url:'http://localhost:8080/AssignmentDW/processStreet.php',
                method: 'POST',
                data: JSON.stringify(data),
                success : function (responseData) {
                    alert(responseData.message);
                    // loadData();
                    // $('form[name=formCity]').trigger("reset");
                    window.location.href = "http://localhost:8080/AssignmentDW/listCity.php";
                },
                error:function () {
                    alert('something error');

                }
            });
        });

        function loadData() {
            $.ajax({
                url:'http://localhost:8080/AssignmentDW/listJSONDistrict.php',
                method: 'get',
                success: function (responseData) {
                    var disableOption = '<option value="0" selected disabled>--All--</option>';
                    var data = responseData.data;
                    var districtHTML = '';
                    data.forEach(element => {
                        districtHTML += `
                        <option value="${element.id}">${element.name}</option>
                        `;
                    })

                    $('#load_district').html(districtHTML);
                    $('#flitter_district').html(disableOption+districtHTML);
                    // $('#content').append(contentHTML);
                    // console.log(data);
                },
                error:function (error) {
                    alert(error);
                }
            });
        }
        loadData();
        $('#reload').click(loadData);
        $('#listCity').click(function () {
            window.location.href = "http://localhost:8080/AssignmentDW/listCity.php";
        });
    });


</script>

</body>
</html>