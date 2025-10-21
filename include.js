async function includeHTML() {
  const elements = document.querySelectorAll('[data-include]');
  for (const el of elements) {
    const file = el.getAttribute('data-include');
    try {
      const response = await fetch(file);
      if (response.ok) {
        el.innerHTML = await response.text();
      } else {
        el.innerHTML = "Content not found.";
      }
    } catch {
      el.innerHTML = "Error loading include.";
    }
  }
}

document.addEventListener("DOMContentLoaded", includeHTML);