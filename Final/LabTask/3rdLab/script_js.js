document.getElementById("loadBtn").addEventListener("click", loadStudents);

async function loadStudents() {
    try {
        const response = await fetch("students.php");

        if (!response.ok) {
            throw new Error("HTTP error: " + response.status);
        }

        const students = await response.json();

        let output = "";

        students.forEach(s => {
            output += `
                <div class="card">
                    <p><b>Name:</b> ${s.name}</p>
                    <p><b>ID:</b> ${s.id}</p>
                    <p><b>Dept:</b> ${s.dept}</p>
                    <p><b>CGPA:</b> ${s.cgpa}</p>
                </div>
            `;
        });

        document.getElementById("output").innerHTML = output;

    } catch (error) {
        console.error("Error:", error);
        document.getElementById("output").innerHTML =
            "<p style='color:red;'>Failed to load data</p>";
    }
}