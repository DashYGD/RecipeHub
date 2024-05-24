function toggleSection(sectionId) {
    var sections = document.querySelectorAll('.sections');
    sections.forEach(function(section) {
        if (section.id === sectionId) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
    localStorage.setItem('previousSection', sectionId);
}

document.addEventListener('DOMContentLoaded', function() {
    var previousSectionId = localStorage.getItem('previousSection');
    if (previousSectionId) {
        toggleSection(previousSectionId);
    }
});
