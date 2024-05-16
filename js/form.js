document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.getElementById("option");
    const blocks = [document.getElementById("d1"), document.getElementById("d2"), document.getElementById("d3")];

    selectElement.addEventListener("change", (e) => {
        const selectedIndex = e.target.selectedIndex;
        blocks.forEach((e) => {
            e.style.display = "none";
        });
        if (selectedIndex > 0) {
            blocks[selectedIndex - 1].style.display = "block";
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../selection.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('opt=' + selectedIndex);
    });
});
