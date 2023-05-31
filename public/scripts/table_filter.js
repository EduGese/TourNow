function sortTable(columnIndex) {
    const table = document.querySelector(".miTabla");
    const rows = Array.from(table.rows);
    const isAscending = table.classList.toggle("ascend", ! table.classList.contains("ascend"));
    
    rows.sort((rowA, rowB) => {
    const cellA = rowA.cells[columnIndex].textContent.trim();
    const cellB = rowB.cells[columnIndex].textContent.trim();
    return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });
    
    while (table.rows.length > 1) {
    table.deleteRow(1);
    }
    
    rows.forEach((row) => {
    table.appendChild(row);
    });
    }