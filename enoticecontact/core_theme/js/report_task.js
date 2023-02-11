//postMessage("I\'m working before postMessage(\'ali\').");

onmessage = function(oEvent) {
    var size = Object.keys(oEvent.data.tableData).length;
    //postMessage('Hi ' + size);
    var columns = oEvent.data.cols;
    var colCount = Object.keys(oEvent.data.cols).length;
    //console.log(oEvent.data.cols);
    //console.log(Object.keys(oEvent.data.cols).length);
    for(i=0;i<=size-1;i++){
        var row = '';
        var dataTemp = oEvent.data.tableData[i]
        for(j=0;j<=colCount-1;j++){
            row += '<td>'+dataTemp[columns[j]]+'</td>';
        }
        row = '<tr>'+row+'</tr>';
        postMessage({action:'append-row',row:row});
    }
    postMessage({action:'done-all',size:size});
};