<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload large CSV file</title>
    <style>
        body{font-size:8pt; color:#333}
        #wrap{width:500px; margin:5px auto}
        #response{height:200px; overflow-y:scroll; border:1px solid #ddd;}
    </style>
    <style>
        #myProgress {
            width: 100%;
            background-color: grey;
        }

        #myBar {
            width: 1%;
            height: 30px;
            background-color: green;
        }
    </style>
</head>

<body>
<div id="wrap">
    <ul id="response">

    </ul><!-- // response -->
    <form id="myForm">
        <input type="file" id="csvFile" accept=".csv" />
        <br />
        <input id="btnUpload" type="submit" value="Upload" />
    </form>
    <div id="myProgress">
        <div id="myBar"></div>
    </div>

</div>
<script>
    const myForm = document.getElementById("myForm");
    const csvFile = document.getElementById("csvFile");
    myForm.addEventListener("submit", function (e) {
        console.log('Start');
        e.preventDefault();
        //$('#btnUpload').prop('disabled', true);
        $('#response').empty();
        const input = csvFile.files[0];
        const reader = new FileReader();
        reader.readAsText(input);
        reader.onload = function (e) {
            console.log('Start upload');
            const str = e.target.result;
            // slice from start of text to the first \n index
            // use split to create an array from string by delimiter
            let delimiter = ',';
            const headers = str.slice(0, str.indexOf("\n")).split(delimiter);
            // slice from \n index + 1 to the end of the text
            // use split to create an array of each csv value row
            const rows = str.slice(str.indexOf("\n") + 1).split("\n");
            post_rows(rows);
        };
    });
    /**
     * Send data to server using AJAX
     */
    async function post_rows(rows)
    {
        let elem = document.getElementById("myBar");
        let i = 1;
        let width = 1;
        for (const row of rows) {
            try {
                await   $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    // async: true,
                    data: {
                        action : 'my_ajax_action',
                        data_chank :  row
                    }
                }).done(function(response) {
                    console.log(response);
                    var line = JSON.parse(response);
                    if(Array.isArray(line)) {
                        line.forEach(function (cline) {
                            $('#response').append('<li>' + cline + '</li>');
                        })
                    }
                    width += i/(rows.length / 100);
                    elem.style.width = width + "%";
                });
            } catch (error) {
                console.log(error);
            }
        }
    }
</script>
</body>
</html>
