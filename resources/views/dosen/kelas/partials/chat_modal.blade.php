<div id="chatModal"
class="fixed inset-0 z-[300] hidden items-start md:items-center justify-center
       bg-[#b9cbe4]/75 backdrop-blur-sm pt-16 md:pt-0 pl-0 px-0 md:px-4">
  <div class="relative w-[calc(100%-80px)] h-[calc(100dvh-64px)] max-w-none max-h-none rounded-none md:w-[720px] md:h-[720px] md:max-w-[95vw] md:max-h-[90vh] md:rounded-2xl bg-gradient-to-br from-[#d8e5f1] via-[#d2e7f2] to-[#c2e4f1] border border-sky-200/70 shadow-xl animate-scaleIn flex flex-col overflow-hidden">
    <div id="chatHeader" class="flex items-center justify-between px-5 py-4 border-b border-sky-200/80 bg-white/35 transition-transform duration-300 ease-out">
      <button type="button" onclick="closeChatModal()" class="text-gray-500 hover:text-gray-700">
        <span class="material-symbols-rounded text-2xl">chevron_left</span>
      </button>
      <h3 id="chatModalTitle" class="text-lg font-semibold text-gray-800">
        Diskusi Kelas
      </h3>
      <span class="w-6"></span>
    </div>

    <div id="chatMessages" class="flex-1 p-5 space-y-3 overflow-y-auto bg-gradient-to-b from-[#d9eaf4]/70 to-[#c5deeb]/60 transition-transform duration-300 ease-out"></div>

    <div id="chatComposer" class="border-t border-sky-200/80 p-4 bg-white/35 transition-transform duration-300 ease-out">
      <form id="chatForm" class="flex items-end gap-2">
        <button
          id="chatAttachBtn"
          type="button"
          class="h-10 w-10 shrink-0 rounded-xl border border-sky-200 bg-white/90 text-sky-600 hover:bg-sky-50"
          title="Tambah lampiran"
        >
          +
        </button>
        <input id="chatAttachment" type="file" class="hidden">
        <textarea
          id="chatInput"
          rows="1"
          placeholder="Tulis pesan diskusi..."
          class="w-full resize-none rounded-xl border border-sky-200 bg-white/85 px-3 py-2 text-sm leading-5 focus:outline-none focus:ring-2 focus:ring-sky-400"
        ></textarea>
        <button
          id="chatSubmitBtn"
          type="submit"
          class="h-10 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 px-4 text-sm font-semibold text-white hover:from-sky-600 hover:to-blue-700"
        >
          Kirim
        </button>
      </form>
      <p id="chatAttachmentInfo" class="mt-1 hidden text-xs text-slate-600"></p>
    </div>

    <div id="contactPanel" class="absolute inset-y-0 right-0 z-40 w-[340px] max-w-[90%] translate-x-full bg-gradient-to-b from-[#f5f8fb]/80 to-[#e7edf3]/80 border-l border-slate-300 shadow-xl transition-transform duration-300 ease-out">
      <div id="contactPanelCard" class="mx-2 my-2 h-[calc(100%-16px)] rounded-2xl border border-slate-200/80 bg-gradient-to-b from-[#f5f8fb] to-[#e7edf3] shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-4 border-b border-slate-300 bg-white/70">
          <button type="button" id="btnCloseContactPanel" class="text-slate-600 hover:text-slate-800">
            <span class="material-symbols-rounded text-xl">arrow_back</span>
          </button>
          <h4 class="text-xl font-medium text-slate-800">Info kontak</h4>
          <span class="w-6"></span>
        </div>
        <div class="p-6 text-center">
          <img id="contactPanelPhoto" src="" alt="Foto kontak" class="mx-auto h-44 w-44 rounded-full object-cover border border-white shadow">
          <p id="contactPanelName" class="mt-5 text-3xl font-semibold text-slate-800"></p>
          <p id="contactPanelRole" class="mt-1 text-base text-slate-500"></p>
          <p id="contactPanelIdentity" class="mt-2 text-2xl text-slate-700"></p>
          <div class="mt-5 rounded-xl border border-slate-200 bg-white/80 p-4 text-left shadow-sm space-y-2">
            <div>
              <p id="contactPanelField1Label" class="text-xs uppercase tracking-wide text-slate-400">Fakultas</p>
              <p id="contactPanelField1Value" class="text-sm font-medium text-slate-700">-</p>
            </div>
            <div>
              <p id="contactPanelField2Label" class="text-xs uppercase tracking-wide text-slate-400">Prodi</p>
              <p id="contactPanelField2Value" class="text-sm font-medium text-slate-700">-</p>
            </div>
            <div>
              <p id="contactPanelField3Label" class="text-xs uppercase tracking-wide text-slate-400">No. HP</p>
              <p id="contactPanelField3Value" class="text-sm font-medium text-slate-700">-</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let chatUserMap = {};
  const currentUserId = Number(@json(session('user_id')));
  const currentUserName = @json(session('name') ?? '-');
  const defaultChatBaseUrlTemplate = @json(route('dosen.kelas.diskusi.index', ['kelas' => '__KELAS_ID__']));
  const defaultChatMessageUrlTemplate = @json(route('dosen.kelas.diskusi.update', ['kelas' => '__KELAS_ID__', 'diskusi' => '__DISKUSI_ID__']));
  const unreadStatusUrl = window.__chatUnreadStatusUrl || @json(route('dosen.diskusi.unread_status'));
  const markReadUrl = window.__chatMarkReadUrl || @json(route('dosen.diskusi.mark_read'));
  const defaultAvatar = @json(asset('img/default_profil.jpg'));
  const csrfToken = @json(csrf_token());

  function getChatBaseUrlTemplate() {
    return window.__chatBaseUrlTemplate || defaultChatBaseUrlTemplate;
  }

  function getChatMessageUrlTemplate() {
    return window.__chatMessageUrlTemplate || defaultChatMessageUrlTemplate;
  }

  function chatUrl(kelasId) {
    return getChatBaseUrlTemplate()
      .replace('__KELAS_ID__', String(kelasId))
      .replace('__CTX_ID__', String(kelasId));
  }

  function chatMessageUrl(kelasId, diskusiId) {
    return getChatMessageUrlTemplate()
      .replace('__KELAS_ID__', String(kelasId))
      .replace('__CTX_ID__', String(kelasId))
      .replace('__DISKUSI_ID__', String(diskusiId));
  }

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function formatFileSize(bytes) {
    const value = Number(bytes || 0);
    if (!Number.isFinite(value) || value <= 0) return '';
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = value;
    let idx = 0;
    while (size >= 1024 && idx < units.length - 1) {
      size /= 1024;
      idx += 1;
    }
    return `${size.toFixed(size >= 10 || idx === 0 ? 0 : 1)} ${units[idx]}`;
  }

  function renderAttachment(item) {
    const url = item?.lampiran_url;
    if (!url) return '';
    const mime = String(item?.lampiran_mime || '').toLowerCase();
    const name = escapeHtml(item?.lampiran_name || 'Lampiran');
    const size = formatFileSize(item?.lampiran_size);
    const meta = size ? ` • ${escapeHtml(size)}` : '';
    const safeUrl = escapeHtml(url);
    if (mime.startsWith('image/')) {
      return `<a href="${safeUrl}" target="_blank" class="mt-1 block"><img src="${safeUrl}" class="max-h-56 rounded-md border border-white/40 object-contain" alt="${name}"><span class="mt-1 block text-[10px] opacity-80">${name}${meta}</span></a>`;
    }
    if (mime.startsWith('video/')) {
      return `<div class="mt-1"><video controls class="max-h-56 w-full rounded-md border border-white/40"><source src="${safeUrl}" type="${escapeHtml(mime)}"></video><a href="${safeUrl}" target="_blank" class="mt-1 block text-[10px] opacity-80">${name}${meta}</a></div>`;
    }
    if (mime === 'application/pdf') {
      return `<a href="${safeUrl}" target="_blank" class="mt-1 block w-[70%] min-w-[170px]"><div class="overflow-hidden rounded-md border border-white/40 bg-white/80"><embed src="${safeUrl}#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" class="h-56 w-full"><span class="block border-t border-slate-200 px-2 py-1 text-[10px] text-slate-700">${name}${meta}</span></div></a>`;
    }
    return `<a href="${safeUrl}" target="_blank" class="mt-1 block rounded-md border border-white/40 bg-white/70 px-2 py-1 text-[11px] text-slate-700">File: ${name}${meta}</a>`;
  }

  function nameColorClass(name) {
    const rainbow = [
      'text-red-500',
      'text-orange-500',
      'text-amber-500',
      'text-yellow-500',
      'text-lime-500',
      'text-green-500',
      'text-emerald-500',
      'text-cyan-500',
      'text-sky-500',
      'text-blue-500',
      'text-indigo-500',
      'text-violet-500',
      'text-fuchsia-500',
      'text-pink-500',
      'text-rose-500',
    ];
    const raw = String(name || '');
    let hash = 0;
    for (let i = 0; i < raw.length; i += 1) {
      hash = ((hash << 5) - hash) + raw.charCodeAt(i);
      hash |= 0;
    }
    const index = Math.abs(hash) % rainbow.length;
    return rainbow[index];
  }

  function closeAllChatMenus() {
    document.querySelectorAll('[id^="chat-menu-"]').forEach((menu) => menu.classList.add('hidden'));
  }

  function toggleMobileSidebarForChat(hide) {
    if (window.innerWidth > 767) return;
    const sidebar = document.querySelector('.sidebar');
    const sidebarBtn = document.querySelector('.sidebar-menu-button');
    if (hide) {
      sidebar?.classList.add('hidden');
      sidebarBtn?.classList.add('hidden');
      document.body.classList.add('chat-mobile-open');
      return;
    }
    sidebar?.classList.remove('hidden');
    sidebarBtn?.classList.remove('hidden');
    document.body.classList.remove('chat-mobile-open');
  }

  function currentChatType() {
    return window.__chatContextType || 'kelas';
  }

  function getChatButtons() {
    return Array.from(document.querySelectorAll('button[onclick*="openChatModal"][data-kelas-id]'));
  }

  function setUnreadDot(button, show) {
    if (!button) return;
    let dot = button.querySelector('.chat-unread-dot');
    if (!show) {
      if (dot) dot.remove();
      return;
    }
    if (!dot) {
      dot = document.createElement('span');
      dot.className = 'chat-unread-dot absolute -top-1 -right-1 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white';
      if (!button.classList.contains('relative')) {
        button.classList.add('relative');
      }
      button.appendChild(dot);
    }
  }

  async function refreshUnreadDots() {
    const buttons = getChatButtons();
    const ids = [...new Set(buttons.map((b) => Number(b.dataset.kelasId || 0)).filter((v) => v > 0))];
    if (!ids.length) return;

    const params = new URLSearchParams({
      type: currentChatType(),
      ids: ids.join(','),
    });

    try {
      const res = await fetch(`${unreadStatusUrl}?${params.toString()}`, {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin',
      });
      const data = await res.json();
      const map = data?.data || {};
      buttons.forEach((btn) => {
        const id = String(btn.dataset.kelasId || '');
        setUnreadDot(btn, Boolean(map[id]));
      });
    } catch (_) {}
  }

  async function markContextAsRead(contextId) {
    try {
      await fetch(markReadUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        credentials: 'same-origin',
        body: JSON.stringify({
          type: currentChatType(),
          id: Number(contextId || 0),
        }),
      });
    } catch (_) {}
  }

  function getContactByUserId(userId) {
    return chatUserMap[String(userId)] || {};
  }

  function getContactByName(name) {
    const target = String(name || '').toLowerCase().trim();
    if (!target) return {};
    const list = Object.values(chatUserMap || {});
    return list.find((c) => String(c?.name || '').toLowerCase().trim() === target) || {};
  }

  function resolveContactFromMessage(item) {
    const byId = getContactByUserId(item.user_id);
    const byName = getContactByName(item.user_name);

    if (byId?.name) return byId;
    if (byName?.name) return byName;
    return {};
  }

  function isContactPanelOpen() {
    return !document.getElementById('contactPanel')?.classList.contains('translate-x-full');
  }

  function updateMineMessageOffsets() {
    const panel = document.getElementById('contactPanel');
    const composer = document.getElementById('chatComposer');
    const form = document.getElementById('chatForm');
    const panelOpen = isContactPanelOpen();
    const rightPad = panelOpen ? ((panel?.offsetWidth || 0) + 12) : 0;
    const baseComposerPad = 16;
    const smoothTiming = '420ms cubic-bezier(0.22, 1, 0.36, 1)';

    document.querySelectorAll('.chat-item.mine').forEach((el) => {
      el.style.transition = `padding-right ${smoothTiming}`;
      el.style.paddingRight = `${rightPad}px`;
    });

    if (composer) {
      composer.style.transition = `padding-right ${smoothTiming}`;
      composer.style.paddingRight = `${baseComposerPad + rightPad}px`;
    }
    if (form) {
      form.style.transition = `transform ${smoothTiming}`;
      form.style.transform = panelOpen ? 'translateX(-8px)' : 'translateX(0)';
    }
  }

  function autoResizeChatInput() {
    const input = document.getElementById('chatInput');
    if (!input) return;

    const styles = window.getComputedStyle(input);
    const lineHeight = parseFloat(styles.lineHeight) || 20;
    const paddingTop = parseFloat(styles.paddingTop) || 0;
    const paddingBottom = parseFloat(styles.paddingBottom) || 0;
    const oneRowHeight = lineHeight + paddingTop + paddingBottom;
    const maxHeight = (lineHeight * 7) + paddingTop + paddingBottom;

    input.style.height = 'auto';
    const measured = input.scrollHeight > 0 ? input.scrollHeight : oneRowHeight;
    const nextHeight = Math.max(oneRowHeight, Math.min(measured, maxHeight));
    input.style.height = `${nextHeight}px`;
    input.style.overflowY = input.scrollHeight > maxHeight ? 'auto' : 'hidden';
  }

  function renderDateSeparator(label) {
    const row = document.createElement('div');
    row.className = 'w-full flex justify-center my-2';
    row.innerHTML = `<span data-chat-date="${escapeHtml(label || '-')}" class="inline-flex items-center rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 shadow-sm border border-sky-200/70">${escapeHtml(label || '-')}</span>`;
    return row;
  }

  function renderChatMessage(item) {
    const isMine = Number(item.user_id) === currentUserId;
    const contact = resolveContactFromMessage(item);
    const senderDisplayName = (!isMine && contact.role === 'dosen' && contact.gelar)
      ? `${item.user_name || '-'} ${contact.gelar}`
      : (item.user_name || '-');
    const panelOpen = isContactPanelOpen();
    const wrapper = document.createElement('div');
    wrapper.className = `chat-item ${isMine ? 'mine' : 'other'} w-full flex ${isMine ? 'justify-end' : 'justify-start'} transition-[padding] duration-300 ease-out`;
    if (isMine && panelOpen) {
      const panel = document.getElementById('contactPanel');
      wrapper.style.paddingRight = `${(panel?.offsetWidth || 0) + 12}px`;
    }
    const senderName = escapeHtml(senderDisplayName);
    const senderColor = nameColorClass(item.user_name || '-');

    const menuHtml = isMine ? `
      <div class="relative ml-2">
        <button type="button" data-action="toggle-menu" data-message-id="${item.id}" class="rounded-full p-1 text-slate-500 hover:bg-slate-200">
          <span class="material-symbols-rounded text-base">more_vert</span>
        </button>
        <div id="chat-menu-${item.id}" class="hidden absolute right-0 mt-1 w-28 rounded-lg border border-slate-200 bg-white shadow-lg z-10">
          <button type="button" data-action="edit-message" data-message-id="${item.id}" data-message-text="${escapeHtml(item.pesan || '')}" class="w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Edit</button>
          <button type="button" data-action="delete-message" data-message-id="${item.id}" class="w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">Hapus</button>
        </div>
      </div>` : '';

    const renderedPesan = escapeHtml(item.pesan || '').replace(/\n/g, '<br>');
    const attachmentHtml = renderAttachment(item);

    wrapper.innerHTML = `
      <div class="flex items-start gap-1 max-w-[92%] md:max-w-[82%]">
        ${!isMine ? `
          <button
            type="button"
            data-action="open-contact"
            data-contact-id="${item.user_id}"
            data-contact-name-raw="${escapeHtml(item.user_name || '-')}"
            class="mt-0.5 rounded-full hover:scale-105 transition-transform"
          >
            <img
              src="${escapeHtml(contact.foto || item.user_foto || defaultAvatar)}"
              alt="${senderName}"
              onerror="this.onerror=null;this.src='${defaultAvatar}';"
              class="h-7 w-7 rounded-full object-cover border border-white/70 shadow-sm hover:ring-2 hover:ring-sky-300"
            >
          </button>
        ` : ''}
        <div class="min-w-0 max-w-full rounded-sm px-[9px] py-[3px] text-[13px] leading-[1.2] shadow break-words whitespace-normal ${isMine ? 'text-white border border-sky-500/40 bg-gradient-to-br from-sky-400/40 to-blue-600/40' : 'text-slate-800 border border-slate-200 bg-white'}">
          ${!isMine ? `<button type="button" data-action="open-contact" data-contact-id="${item.user_id}" data-contact-name-raw="${escapeHtml(item.user_name || '-')}" class="mb-0.5 inline-flex items-center gap-1 rounded px-1 py-0.5 text-[11px] font-semibold ${senderColor} hover:bg-sky-50 hover:underline decoration-2 underline-offset-2 transition">${senderName}</button>` : ''}
          <div class="flex items-end gap-1">
            <div class="min-w-0 flex-1">
              ${renderedPesan ? `<div>${renderedPesan}</div>` : ''}
              ${attachmentHtml}
            </div>
            <div class="shrink-0 text-[9px] leading-none ${isMine ? 'text-sky-100' : 'text-slate-500'} mb-[1px]">
              ${escapeHtml(item.jam || '')}
            </div>
          </div>
        </div>
        ${menuHtml}
      </div>
    `;

    return wrapper;
  }

  async function loadChatMessages(kelasId) {
    const messages = document.getElementById('chatMessages');
    messages.innerHTML = '<div data-chat-state="loading" class="text-sm text-slate-500">Memuat chat...</div>';

    try {
      const response = await fetch(chatUrl(kelasId), {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
      });
      const data = await response.json();
      if (data && typeof data.user_map === 'object' && data.user_map) {
        chatUserMap = data.user_map;
      }
      if (data && typeof data.dosen_contact === 'object' && data.dosen_contact) {
        chatUserMap = {
          ...(chatUserMap || {}),
          ...(data.dosen_contact.user_id ? { [String(data.dosen_contact.user_id)]: data.dosen_contact } : {}),
        };
      }
      const rows = Array.isArray(data.messages) ? data.messages : [];

      messages.innerHTML = '';
      if (!rows.length) {
        messages.innerHTML = '<div data-chat-state="empty" class="text-sm text-slate-500">Belum ada pesan.</div>';
      } else {
        let lastTanggal = null;
        rows.forEach((item) => {
          if (item.tanggal !== lastTanggal) {
            messages.appendChild(renderDateSeparator(item.tanggal));
            lastTanggal = item.tanggal;
          }
          messages.appendChild(renderChatMessage(item));
        });
        messages.scrollTop = messages.scrollHeight;
      }
      updateMineMessageOffsets();
    } catch (error) {
      messages.innerHTML = '<div class="text-sm text-red-600">Gagal memuat chat.</div>';
    }
  }

  function applyChatContext(button) {
    const title = document.getElementById('chatModalTitle');
    const messages = document.getElementById('chatMessages');
    const kelasNama = button?.dataset?.kelasNama;
    const kelasId = button?.dataset?.kelasId;
    const form = document.getElementById('chatForm');
    const submitBtn = document.getElementById('chatSubmitBtn');
    const input = document.getElementById('chatInput');
    let userMap = {};
    try {
      userMap = JSON.parse(button?.dataset?.userMap || '{}');
    } catch (_) {
      userMap = {};
    }
    chatUserMap = userMap && typeof userMap === 'object' ? userMap : {};
    closeContactPanel();

    title.textContent = kelasNama ? `Diskusi Kelas - ${kelasNama}` : 'Diskusi Kelas';
    window.__activeChatContextType = String(window.__chatContextType || 'kelas');
    window.__activeChatContextId = String(kelasId || '');
    form.dataset.kelasId = kelasId || '';
    form.dataset.editId = '';
    if (submitBtn) submitBtn.textContent = 'Kirim';
    if (input) input.value = '';
    if (messages) {
      messages.innerHTML = '<div data-chat-state="loading" class="text-sm text-slate-500">Memuat pesan...</div>';
    }

    document.querySelectorAll(`button[onclick*="openChatModal"][data-kelas-id="${kelasId}"]`).forEach((btn) => setUnreadDot(btn, false));
    markContextAsRead(kelasId);

    if (kelasId) {
      loadChatMessages(kelasId);
    }
  }

  function openChatModal(button) {
    const modal = document.getElementById('chatModal');
    const splitWithNavbar = window.__chatSplitWithNavbar === true;
    modal.classList.toggle('navbar-split-chat', splitWithNavbar);
    window.__chatSplitWithNavbar = false;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    toggleMobileSidebarForChat(true);
    requestAnimationFrame(autoResizeChatInput);
    applyChatContext(button);
  }

  function switchChatContextFromNavbar(button) {
    const modal = document.getElementById('chatModal');
    if (!modal) return;
    modal.classList.add('navbar-split-chat');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    toggleMobileSidebarForChat(true);
    applyChatContext(button);
  }

  function closeChatModal() {
    const modal = document.getElementById('chatModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modal.classList.remove('navbar-split-chat');
    window.__activeChatContextType = '';
    window.__activeChatContextId = '';
    closeAllChatMenus();
    closeContactPanel();
    toggleMobileSidebarForChat(false);
    window.dispatchEvent(new CustomEvent('navbar-chat-closed'));
  }

  function openContactPanel(contact) {
    const panel = document.getElementById('contactPanel');
    const photoEl = document.getElementById('contactPanelPhoto');
    const nameEl = document.getElementById('contactPanelName');
    const roleEl = document.getElementById('contactPanelRole');
    const identityEl = document.getElementById('contactPanelIdentity');
    const field1LabelEl = document.getElementById('contactPanelField1Label');
    const field1ValueEl = document.getElementById('contactPanelField1Value');
    const field2LabelEl = document.getElementById('contactPanelField2Label');
    const field2ValueEl = document.getElementById('contactPanelField2Value');
    const field3LabelEl = document.getElementById('contactPanelField3Label');
    const field3ValueEl = document.getElementById('contactPanelField3Value');

    if (!panel || !photoEl || !nameEl || !identityEl) return;

    const isMahasiswa = String(contact?.role || '') === 'mahasiswa';
    const displayName = contact?.display_name || contact?.name || '-';
    const roleLabel = isMahasiswa ? 'Mahasiswa' : 'Dosen';

    photoEl.src = contact?.foto || defaultAvatar;
    nameEl.textContent = displayName;
    if (roleEl) roleEl.textContent = roleLabel;
    identityEl.textContent = isMahasiswa ? (contact?.nim || '-') : (contact?.nidn || contact?.phone || '-');

    if (field1LabelEl) field1LabelEl.textContent = 'Fakultas';
    if (field1ValueEl) field1ValueEl.textContent = contact?.fakultas || '-';
    if (field2LabelEl) field2LabelEl.textContent = 'Prodi';
    if (field2ValueEl) field2ValueEl.textContent = contact?.prodi || '-';
    if (field3LabelEl) field3LabelEl.textContent = 'No. HP';
    if (field3ValueEl) field3ValueEl.textContent = contact?.phone || '-';

    panel.classList.remove('translate-x-full');
    updateMineMessageOffsets();
  }

  function closeContactPanel() {
    const panel = document.getElementById('contactPanel');
    panel?.classList.add('translate-x-full');
    updateMineMessageOffsets();
  }

  document.getElementById('chatModal')?.addEventListener('click', function (e) {
    if (isContactPanelOpen() && !e.target.closest('#contactPanelCard')) {
      closeContactPanel();
    }
    if (e.target === this) {
      if (this.classList.contains('navbar-split-chat')) return;
      closeChatModal();
    }
  });

  document.getElementById('chatMessages')?.addEventListener('click', function (e) {
    const toggleBtn = e.target.closest('button[data-action="toggle-menu"]');
    const editBtn = e.target.closest('button[data-action="edit-message"]');
    const deleteBtn = e.target.closest('button[data-action="delete-message"]');
    const openContactBtn = e.target.closest('button[data-action="open-contact"]');
    const form = document.getElementById('chatForm');
    const input = document.getElementById('chatInput');
    const submitBtn = document.getElementById('chatSubmitBtn');
    const kelasId = form?.dataset?.kelasId;

    if (openContactBtn) {
      e.stopPropagation();
      const userId = openContactBtn.dataset.contactId || '';
      const contactNameRaw = openContactBtn.dataset.contactNameRaw || '';
      let contact = getContactByUserId(userId);
      if (!contact?.name) {
        contact = getContactByName(contactNameRaw);
      }
      if (!contact?.name || String(contact.name) === String(currentUserName)) {
        closeAllChatMenus();
        return;
      }
      const baseName = contact.name || '-';
      const displayName = (contact.role === 'dosen' && contact.gelar) ? `${baseName} ${contact.gelar}` : baseName;
      openContactPanel({
        ...contact,
        display_name: displayName,
      });
      closeAllChatMenus();
      return;
    }

    if (toggleBtn) {
      const messageId = toggleBtn.dataset.messageId;
      const menu = document.getElementById(`chat-menu-${messageId}`);
      if (!menu) return;
      const isOpen = !menu.classList.contains('hidden');
      closeAllChatMenus();
      if (!isOpen) menu.classList.remove('hidden');
      return;
    }

    if (editBtn) {
      form.dataset.editId = editBtn.dataset.messageId || '';
      input.value = editBtn.dataset.messageText || '';
      const attachmentInput = document.getElementById('chatAttachment');
      const attachmentInfo = document.getElementById('chatAttachmentInfo');
      if (attachmentInput) attachmentInput.value = '';
      if (attachmentInfo) {
        attachmentInfo.textContent = '';
        attachmentInfo.classList.add('hidden');
      }
      input.focus();
      autoResizeChatInput();
      if (submitBtn) submitBtn.textContent = 'Update';
      closeAllChatMenus();
      return;
    }

    if (deleteBtn && kelasId) {
      const messageId = deleteBtn.dataset.messageId;
      if (!messageId) return;

      fetch(chatMessageUrl(kelasId, messageId), {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        credentials: 'same-origin'
      })
        .then(async (response) => {
          const data = await response.json();
          if (!response.ok) throw new Error(data.message || 'Gagal menghapus pesan.');
          return data;
        })
        .then(() => loadChatMessages(kelasId))
        .catch(() => alert('Gagal menghapus pesan.'));
      return;
    }

    closeAllChatMenus();
  });

  document.addEventListener('click', function (e) {
    const insideMenu = e.target.closest('[id^="chat-menu-"], [data-action="toggle-menu"]');
    if (!insideMenu) closeAllChatMenus();
  });

  document.getElementById('chatForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.currentTarget;
    const kelasId = form?.dataset?.kelasId;
    const editId = form?.dataset?.editId;
    const input = document.getElementById('chatInput');
    const attachmentInput = document.getElementById('chatAttachment');
    const attachmentInfo = document.getElementById('chatAttachmentInfo');
    const messages = document.getElementById('chatMessages');
    const submitBtn = document.getElementById('chatSubmitBtn');
    const text = (input?.value || '').trim();
    const file = attachmentInput?.files?.[0] || null;
    if ((!text && !file) || !kelasId) return;
    if (editId && file) {
      alert('Lampiran hanya bisa ditambahkan saat kirim pesan baru.');
      return;
    }

    const endpoint = editId ? chatMessageUrl(kelasId, editId) : chatUrl(kelasId);
    let method = editId ? 'PUT' : 'POST';
    let headers = {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    };
    let body;
    if (file) {
      const fd = new FormData();
      if (text) fd.append('pesan', text);
      fd.append('lampiran', file);
      body = fd;
      method = 'POST';
    } else {
      headers['Content-Type'] = 'application/json';
      body = JSON.stringify({ pesan: text });
    }

    fetch(endpoint, {
      method,
      headers,
      credentials: 'same-origin',
      body,
    })
      .then(async (response) => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Gagal kirim pesan.');
        return data;
      })
      .then((data) => {
        if (editId) {
          form.dataset.editId = '';
          if (submitBtn) submitBtn.textContent = 'Kirim';
          loadChatMessages(kelasId);
        } else {
          const hasEmptyState = messages.querySelector('[data-chat-state="empty"], [data-chat-state="loading"]');
          if (hasEmptyState) messages.innerHTML = '';
          if (data?.data) {
            const separators = messages.querySelectorAll('[data-chat-date]');
            const lastSeparator = separators.length ? separators[separators.length - 1] : null;
            const latestSeparatorText = lastSeparator ? lastSeparator.getAttribute('data-chat-date') : null;
            if (!latestSeparatorText || latestSeparatorText !== data.data.tanggal) {
              messages.appendChild(renderDateSeparator(data.data.tanggal));
            }
            messages.appendChild(renderChatMessage(data.data));
            messages.scrollTop = messages.scrollHeight;
            updateMineMessageOffsets();
          }
        }
        input.value = '';
        if (attachmentInput) attachmentInput.value = '';
        if (attachmentInfo) {
          attachmentInfo.textContent = '';
          attachmentInfo.classList.add('hidden');
        }
        autoResizeChatInput();
      })
      .catch(() => {
        alert(editId ? 'Gagal mengubah pesan.' : 'Gagal mengirim pesan.');
      });
  });

  document.getElementById('chatAttachBtn')?.addEventListener('click', function () {
    if (document.getElementById('chatForm')?.dataset?.editId) {
      alert('Selesaikan mode edit dulu sebelum tambah lampiran.');
      return;
    }
    document.getElementById('chatAttachment')?.click();
  });
  document.getElementById('chatAttachment')?.addEventListener('change', function () {
    const info = document.getElementById('chatAttachmentInfo');
    const file = this.files?.[0];
    if (!info) return;
    if (!file) {
      info.textContent = '';
      info.classList.add('hidden');
      return;
    }
    info.textContent = `Lampiran: ${file.name}`;
    info.classList.remove('hidden');
  });

  document.getElementById('chatInput')?.addEventListener('input', autoResizeChatInput);
  document.getElementById('chatInput')?.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      const isMobile = window.matchMedia('(max-width: 767px)').matches;
      if (isMobile) return;
      e.preventDefault();
      document.getElementById('chatForm')?.requestSubmit();
    }
  });
  document.getElementById('btnCloseContactPanel')?.addEventListener('click', closeContactPanel);
  document.getElementById('contactPanel')?.addEventListener('click', function (e) {
    if (!e.target.closest('#contactPanelCard')) {
      closeContactPanel();
    }
  });

  refreshUnreadDots();

  window.openChatModal = openChatModal;
  window.closeChatModal = closeChatModal;
  window.switchChatContextFromNavbar = switchChatContextFromNavbar;
</script>
<style>
  #chatModal.navbar-split-chat {
    justify-content: center !important;
    padding-right: 0 !important;
    padding-left: 0 !important;
    padding-top: 0 !important;
    background: transparent !important;
    backdrop-filter: none !important;
    pointer-events: none;
  }
  @media (min-width: 768px) {
    #chatModal.navbar-split-chat > .relative {
      pointer-events: auto;
      transform: translateX(280px);
    }
  }
  @media (max-width: 767px) {
    #chatModal {
      inset: 64px 0 0 0 !important;
      height: calc(100dvh - 64px) !important;
      align-items: stretch !important;
      justify-content: stretch !important;
      padding-top: 0 !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
      z-index: 300 !important;
    }
    #chatModal > .relative {
      width: 100vw !important;
      height: calc(100dvh - 64px) !important;
      border-radius: 0 !important;
      max-width: none !important;
      max-height: none !important;
    }
    #chatModal.navbar-split-chat {
      z-index: 300 !important;
      align-items: stretch !important;
      justify-content: stretch !important;
      padding-top: 0 !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
      background: transparent !important;
      backdrop-filter: none !important;
    }
    #chatModal.navbar-split-chat > .relative {
      pointer-events: auto;
      transform: none !important;
      width: 100vw !important;
      height: calc(100dvh - 64px) !important;
      border-radius: 0 !important;
    }
    #chatComposer {
      position: sticky;
      bottom: 0;
      z-index: 5;
    }
  }
</style>
