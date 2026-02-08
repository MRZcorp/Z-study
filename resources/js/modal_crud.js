const toSnakeCase = (value) => {
  return value
    .replace(/([a-z0-9])([A-Z])/g, '$1_$2')
    .replace(/-/g, '_')
    .toLowerCase();
};

const fillFormFromDataset = (form, dataset) => {
  Object.entries(dataset).forEach(([key, value]) => {
    if (
      key === 'modalTarget' ||
      key === 'storeUrl' ||
      key === 'updateUrl' ||
      key === 'titleAdd' ||
      key === 'titleEdit'
    ) {
      return;
    }

    const snakeKey = toSnakeCase(key);
    const input =
      form.querySelector(`[name="${snakeKey}"]`) ||
      form.querySelector(`[name="${key}"]`) ||
      form.querySelector(`#${snakeKey}`) ||
      form.querySelector(`#${key}`);

    if (!input) {
      return;
    }

    input.value = value ?? '';
  });
};

const getModalElements = (modalId) => {
  if (!modalId) {
    return null;
  }

  const modal = document.getElementById(modalId);
  if (!modal) {
    return null;
  }

  return {
    modal,
    form: modal.querySelector('form.crud-form'),
    methodInput: modal.querySelector('.crud-method'),
    title: modal.querySelector('.modal-title'),
    closeBtn: modal.querySelector('.btn-close'),
  };
};

const openModal = (modal) => modal.classList.remove('hidden');
const closeModal = (modal) => modal.classList.add('hidden');
const closeBound = new WeakSet();

const updateProdiOptions = (fakSelect, prodiSelect) => {
  if (!fakSelect || !prodiSelect) {
    return;
  }

  const selectedFakultas = fakSelect.value;
  const options = Array.from(prodiSelect.options);

  options.forEach((option) => {
    if (!option.value) {
      option.hidden = false;
      return;
    }

    const fakultasId = option.dataset.fakultasId;
    const shouldShow = !selectedFakultas || fakultasId === selectedFakultas;
    option.hidden = !shouldShow;
  });

  if (selectedFakultas && prodiSelect.value) {
    const currentOption = prodiSelect.selectedOptions[0];
    if (currentOption && currentOption.hidden) {
      prodiSelect.value = '';
    }
  }
};

const bindFakultasProdi = (fakSelect) => {
  const prodiTarget = fakSelect?.dataset?.prodiTarget;
  if (!prodiTarget) {
    return;
  }

  const prodiSelect = document.getElementById(prodiTarget);
  updateProdiOptions(fakSelect, prodiSelect);

  fakSelect.addEventListener('change', () => {
    updateProdiOptions(fakSelect, prodiSelect);
  });
};

const bindCloseOnce = (modalElements) => {
  if (!modalElements) {
    return;
  }

  const { modal, closeBtn } = modalElements;
  if (!modal || closeBound.has(modal)) {
    return;
  }

  closeBtn?.addEventListener('click', () => closeModal(modal));
  closeBound.add(modal);
};

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('select[data-prodi-target]').forEach((select) => {
    bindFakultasProdi(select);
  });
  document.querySelectorAll('[data-modal-target].btn-add').forEach((btn) => {
    const modalId = btn.dataset.modalTarget;
    const modalElements = getModalElements(modalId);
    if (!modalElements) {
      return;
    }

    const { modal, form, methodInput, title } = modalElements;
    const storeUrl = btn.dataset.storeUrl;
    const titleAdd = btn.dataset.titleAdd;

    bindCloseOnce(modalElements);

    btn.addEventListener('click', () => {
      if (!form || !methodInput || !title || !storeUrl) {
        return;
      }

      openModal(modal);
      form.action = storeUrl;
      methodInput.value = 'POST';
      title.textContent = titleAdd || title.textContent;
      form.reset();

      const fakultasSelect = form.querySelector('select[data-prodi-target]');
      if (fakultasSelect) {
        updateProdiOptions(fakultasSelect, document.getElementById(fakultasSelect.dataset.prodiTarget));
      }
    });

  });

  document.querySelectorAll('[data-modal-target].btn-edit').forEach((btn) => {
    const modalId = btn.dataset.modalTarget;
    const modalElements = getModalElements(modalId);
    if (!modalElements) {
      return;
    }

    const { modal, form, methodInput, title } = modalElements;
    const updateUrl = btn.dataset.updateUrl;
    const titleEdit = btn.dataset.titleEdit;

    bindCloseOnce(modalElements);

    btn.addEventListener('click', () => {
      if (!form || !methodInput || !title || !updateUrl) {
        return;
      }

      openModal(modal);
      form.action = updateUrl;
      methodInput.value = 'PUT';
      title.textContent = titleEdit || title.textContent;

      fillFormFromDataset(form, btn.dataset);

      const fakultasSelect = form.querySelector('select[data-prodi-target]');
      if (fakultasSelect) {
        updateProdiOptions(fakultasSelect, document.getElementById(fakultasSelect.dataset.prodiTarget));
      }
    });
  });

  document.querySelectorAll('.btn-delete').forEach((btn) => {
    btn.addEventListener('click', () => {
      const deleteUrl = btn.dataset.deleteUrl;
      if (!deleteUrl) {
        return;
      }

      if (!confirm('Yakin hapus data ini?')) {
        return;
      }

      const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');
      const deleteForm = document.createElement('form');
      deleteForm.method = 'POST';
      deleteForm.action = deleteUrl;

      const tokenInput = document.createElement('input');
      tokenInput.type = 'hidden';
      tokenInput.name = '_token';
      tokenInput.value = token || '';

      const methodHidden = document.createElement('input');
      methodHidden.type = 'hidden';
      methodHidden.name = '_method';
      methodHidden.value = 'DELETE';

      deleteForm.appendChild(tokenInput);
      deleteForm.appendChild(methodHidden);

      document.body.appendChild(deleteForm);
      deleteForm.submit();
    });
  });

  document.querySelectorAll('form[data-auto-submit]').forEach((form) => {
    const inputs = form.querySelectorAll('select, input[type="text"], input[type="search"], input[type="number"]');
    let timerId = null;

    const scheduleSubmit = () => {
      if (timerId) {
        clearTimeout(timerId);
      }

      timerId = setTimeout(() => {
        form.submit();
      }, 300);
    };

    inputs.forEach((input) => {
      input.addEventListener('change', scheduleSubmit);
      if (input.tagName.toLowerCase() === 'input') {
        input.addEventListener('input', scheduleSubmit);
      }
    });
  });
});
