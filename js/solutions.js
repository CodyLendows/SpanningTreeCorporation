document.addEventListener("DOMContentLoaded", () => {
  console.log("Solutions page loaded");

  // Add placeholder actions for pricing and demo buttons
  document.querySelectorAll('.btn.pricing').forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      alert("Pricing details coming soon.");
    });
  });

});
