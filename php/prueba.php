<head>
    <script   
   src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js">
    </script>
    <script>

    function scrolify(tblAsJQueryObject, height){
        var oTbl = tblAsJQueryObject;

        // for very large tables you can remove the four lines below
        // and wrap the table with <div> in the mark-up and assign
        // height and overflow property  
        var oTblDiv = $("<div/>");
        oTblDiv.css('height', height);
        oTblDiv.css('overflow','scroll');               
        oTbl.wrap(oTblDiv);

        // save original width
        oTbl.attr("data-item-original-width", oTbl.width());
        oTbl.find('thead tr td').each(function(){
            $(this).attr("data-item-original-width",$(this).width());
        }); 
        oTbl.find('tbody tr:eq(0) td').each(function(){
            $(this).attr("data-item-original-width",$(this).width());
        });                 


        // clone the original table
        var newTbl = oTbl.clone();

        // remove table header from original table
        oTbl.find('thead tr').remove();                 
        // remove table body from new table
        newTbl.find('tbody tr').remove();   

        oTbl.parent().parent().prepend(newTbl);
        newTbl.wrap("<div/>");

        // replace ORIGINAL COLUMN width                
        newTbl.width(newTbl.attr('data-item-original-width'));
        newTbl.find('thead tr td').each(function(){
            $(this).width($(this).attr("data-item-original-width"));
        });     
        oTbl.width(oTbl.attr('data-item-original-width'));      
        oTbl.find('tbody tr:eq(0) td').each(function(){
            $(this).width($(this).attr("data-item-original-width"));
        });                 
    }

    $(document).ready(function(){
        scrolify($('#tblNeedsScrolling'), 160); // 160 is height
    });


    </script>


</head>

<body>
    <div>
        <table border="1" width="100%" id="tblNeedsScrolling">
            <thead>
                <tr><th>Header 1</th><th>Header 2</th></tr>
            </thead>
            <tbody>
                <tr><td>row 1, cell 1</td><td>row 1, cell 2</td></tr>
                <tr><td>row 2, cell 1</td><td>row 2, cell 2</td></tr>
                <tr><td>row 3, cell 1</td><td>row 3, cell 2</td></tr>
                <tr><td>row 4, cell 1</td><td>row 4, cell 2</td></tr>           
                <tr><td>row 5, cell 1</td><td>row 5, cell 2</td></tr>
                <tr><td>row 6, cell 1</td><td>row 6, cell 2</td></tr>
                <tr><td>row 7, cell 1</td><td>row 7, cell 2</td></tr>
                <tr><td>row 8, cell 1</td><td>row 8, cell 2</td></tr>           
            </tbody>
        </table>
    </div>

</body>