<html>
<head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>

 
</head>
<body>
    <!-- <div >
        <table border='1'>
            <tr>
                <td>
                    <strong>Greeting</strong>
                </td>
                <td>
                    <strong>Message</strong>
                </td>
            </tr>
            <tr>
                <td>
                    Hello
                </td>
                <td style="background:red;color:white">
                    World. <mark style="background:white;color:red"> I am hilighted!</mark>
                </td>
            </tr>
        </table>
    </div> -->
    <div class="handsontable" id='data'>
        <table class="htCore">
            <colgroup>
                <col style="width: 50px;">
                <col style="width: 60px;">
                <col style="width: 50px;">
                <col style="width: 65px;">
                <col style="width: 50px;">
                <col style="width: 69px;">
            </colgroup>

            <thead>
                <tr>
                <th>
                    <div class="relative">
                    <span class="colHeader">A</span>
                    </div>
                </th>

                <th>
                    <div class="relative">
                    <span class="colHeader">B</span>
                    </div>
                </th>

                <th>
                    <div class="relative">
                    <span class="colHeader">C</span>
                    </div>
                </th>

                <th>
                    <div class="relative">
                    <span class="colHeader">D</span>
                    </div>
                </th>

                <th>
                    <div class="relative">
                    <span class="colHeader">E</span>
                    </div>
                </th>

                <th>
                    <div class="relative">
                    <span class="colHeader">F</span>
                    </div>
                </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="">dfghdfgh</td>

                    <td class="inputfield"></td>

                    <td class="">dfghdfgh</td>

                    <td class="">dfghdfgh</td>

                    <td class="">dfghdfgh</td>

                    <td class="">dfghdfgh</td>
                </tr>  

            </tbody>
        </table>

    </div>

    <script type='text/javascript'>
        $(document).ready(function()
        {
            $("#btnExport").click(function(e)
            {
                var path = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#data').html());
                window.open(path);

                e.preventDefault();
            });
        });
    </script>

    <input type='button' id='btnExport' value='Export as XLS'>

</body>