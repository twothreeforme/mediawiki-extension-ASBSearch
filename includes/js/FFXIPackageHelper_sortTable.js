// https://stackoverflow.com/questions/55462632/javascript-sort-table-column-on-click
function compareValues(a, b) {
 
  return (a<b) ? -1 : (a>b) ? 1 : 0;
}

function sortTable(colnum) {
  let table = document.getElementById("special-weatherforecast-table");
  let tbody = table.querySelector(`tbody`);
  
  let rows = Array.from(table.querySelectorAll(`tr`));
  rows = rows.slice(1);
  let qs = `td:nth-child(${colnum})`;

  rows.sort( (r1,r2) => {
    let t1 = r1.querySelector(qs);
    let t2 = r2.querySelector(qs);
    return compareValues(Number(t1.textContent),Number(t2.textContent));
  });

  rows.forEach(function(row){
    //console.log(row.cells[1].innerHTML);
    if ( row.cells[1].innerHTML == "0" ) row.cells[1].innerHTML = "Today";
    tbody.appendChild(row);
  });
}
//console.log('here');
sortTable(2);