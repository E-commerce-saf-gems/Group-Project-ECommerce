// imports/common-imports.js
async function loadCommonImports() {
    try {
        const response = await fetch('./common-imports.html');  // Fetch the common imports
        if (!response.ok) throw new Error('Failed to load common imports');
        const importsHTML = await response.text();  // Get the HTML content as a string
        document.head.insertAdjacentHTML('beforeend', importsHTML);  // Insert it into the <head> of the document
    } catch (error) {
        console.error('Error loading common imports:', error);
    }
}

loadCommonImports();  // Call the function to load imports
