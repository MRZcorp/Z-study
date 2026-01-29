console.log('USER JS AKTIF');

document.addEventListener('DOMContentLoaded', () => {
    const btnCloseEdit = document.getElementById('btnCloseEdit');
    btnCloseEdit?.addEventListener('click', () => {
      editModal.classList.add('hidden');
      editModal.classList.remove('flex');
    });

    const editModal = document.getElementById('editUserModal');
    const createModal = document.getElementById('createUserModal');
    const deleteModal = document.getElementById('deleteModal');
    
    let deleteId = null;
    let statusValue = 1;
    
    /* ================= CREATE ================= */
    document.getElementById('btnAddUser')?.addEventListener('click', () => {
      createModal.classList.remove('hidden');
      createModal.classList.add('flex');
    });
    
    /* ================= EDIT ================= */
    document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
    editModal.classList.remove('hidden');
    editModal.classList.add('flex');
    
    document.getElementById('edit_id').value = btn.dataset.id;
    document.getElementById('edit_name').value = btn.dataset.name;
    document.getElementById('edit_email').value = btn.dataset.email;
    document.getElementById('edit_role').value = btn.dataset.role;
    
    statusValue = btn.dataset.status;
    document.getElementById('edit_status').value = statusValue;
    updateStatusUI();
    });
    });
    
    /* STATUS TOGGLE */
    document.getElementById('statusActive').onclick = () => {
    statusValue = 1;
    updateStatusUI();
    };
    
    document.getElementById('statusInactive').onclick = () => {
    statusValue = 0;
    updateStatusUI();
    };
    
    function updateStatusUI(){
    document.getElementById('edit_status').value = statusValue;
    document.getElementById('statusActive').style.opacity = statusValue == 1 ? '1' : '0.3';
    document.getElementById('statusInactive').style.opacity = statusValue == 0 ? '1' : '0.3';
    }
    
    /* ================= DELETE ================= */
    document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = () => {
    deleteId = btn.dataset.id;
    deleteModal.classList.remove('hidden');
    deleteModal.classList.add('flex');
    };
    });
    });

    
  
