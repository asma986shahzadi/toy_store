// Dummy toy data (simulate product names for search functionality)
const toyData = [
    { name: "Super Car" },
    { name: "Dancing Robot" },
    { name: "Barbie Doll" },
    { name: "Lego Set" },
    { name: "Puzzle Cube" },
    { name: "Stuffed Bear" }
];

// Function to search toys
function searchToys() {
    const query = document.getElementById("search").value.toLowerCase().trim();

    if (!query) {
        alert("Please enter a toy name to search.");
        return;
    }

    const results = toyData.filter(toy => toy.name.toLowerCase().includes(query));

    const productListSection = document.querySelector(".product-list");
    const resultHTML = results.map(toy => `
        <div class="product" style="background:#fff; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); width:200px; text-align:center; cursor:pointer; padding:15px; transition:transform 0.3s ease;">
            <img src="assets/images/default_toy.jpg" alt="${toy.name}" style="width:100%; border-radius:5px;">
            <h3 style="margin:15px 0 10px;">${toy.name}</h3>
            <p style="font-weight:bold; color:#007BFF;">$19.99</p>
        </div>
    `).join('');

    productListSection.innerHTML = `
        <h2 style="text-align:center; font-size:28px; margin-bottom:30px;">Search Results</h2>
        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px;">
            ${results.length > 0 ? resultHTML : "<p style='color:#777;'>No toys found.</p>"}
        </div>
    `;
}