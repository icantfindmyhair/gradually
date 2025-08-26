(function(){
  function closeAll() {
    document.querySelectorAll('.menu-dropdown.show').forEach(d => {
      d.classList.remove('show');
      if (d.style.display === 'block') d.style.display = '';
    });
  }

  document.addEventListener('click', (e) => {
    try {
      const menuBtn = e.target.closest('.menu-btn');
      if (menuBtn) {
        e.stopPropagation();
        const menu = menuBtn.closest('.habit-menu');
        const dd = menu.querySelector('.menu-dropdown');
        document.querySelectorAll('.menu-dropdown.show').forEach(d => { if (d !== dd) d.classList.remove('show'); });
        dd.classList.toggle('show');
        dd.style.display = dd.classList.contains('show') ? 'block' : '';
        return;
      }

      const editBtn = e.target.closest('.edit-btn');
      if (editBtn) {
        e.stopPropagation();
        const habitId = editBtn.dataset.habitid;
        if (typeof openEditPopup === 'function') openEditPopup(habitId);
        closeAll();
        return;
      }

      const deleteBtn = e.target.closest('.delete-btn');
      if (deleteBtn) {
        e.stopPropagation();
        const habitId = deleteBtn.dataset.habitid;
        if (confirm('Delete this habit?')) {
          fetch('deleteHabit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'habit_id=' + encodeURIComponent(habitId)
          }).then(r => r.text()).then(() => location.reload());
        }
        closeAll();
        return;
      }

      closeAll();
    } catch (err) {
      console.error('menu error', err);
    }
  });

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });
})();
