function toggleSection(sectionId) {
    var sections = document.querySelectorAll('.sections');
    sections.forEach(function(section) {
        if (section.id === sectionId) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
    // Store the section ID in localStorage
    localStorage.setItem('previousSection', sectionId);
}

// Retrieve the previously opened section on page load
document.addEventListener('DOMContentLoaded', function() {
    var previousSectionId = localStorage.getItem('previousSection');
    if (previousSectionId) {
        toggleSection(previousSectionId);
    }
});
