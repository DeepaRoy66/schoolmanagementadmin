<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fee Assign</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-blue-500">List Assigned Student(s) Fee</h3>
                        <p class="text-gray-500 text-sm mt-1">&raquo; Search assigned fee of student(s).</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="invoiceBtn"
                                class="inline-flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-sky-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9-7-9-7-9 7 9 7z" />
                            </svg>
                            Invoice
                        </button>

                        {{-- TODO: update route name to match your actual create-form route --}}
                        <a href="{{ route('school-admin.fee-assign.create') }}"
                           class="inline-flex items-center gap-2 bg-teal-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            New
                        </a>

                        <button type="button" id="bulkVoidBtn"
                                class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                            </svg>
                            Bulk Void
                        </button>
                    </div>
                </div>

                {{-- NOTE: assumed data shape below. $assignedFees is expected to be a collection/paginator of
                     records exposing: id, student->student_id, student->first_name/last_name,
                     class->name, section->name (nullable), invoice_no, fiscal_year, bill_amount,
                     billingPeriod->name, bill_date, due_date, active (bool).
                     Rename to match your actual model/relations. --}}
                <table id="assignedFeeTable" class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 w-10 no-export"><input type="checkbox" id="selectAllRows" class="rounded border-gray-300"></th>
                            <th class="px-3 py-2 w-10 no-export"></th>
                            <th class="px-3 py-2">SN</th>
                            <th class="px-3 py-2">Student ID</th>
                            <th class="px-3 py-2">Student Name</th>
                            <th class="px-3 py-2">Class</th>
                            <th class="px-3 py-2">Section</th>
                            <th class="px-3 py-2">Invoice No.</th>
                            <th class="px-3 py-2">Fiscal Year</th>
                            <th class="px-3 py-2 text-right">Bill Amount</th>
                            <th class="px-3 py-2">Period Name</th>
                            <th class="px-3 py-2">Bill Date</th>
                            <th class="px-3 py-2">Due Date</th>
                            <th class="px-3 py-2">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assignedFees as $i => $fee)
                            <tr>
                                <td class="px-3 py-2">
                                    <input type="checkbox" class="row-checkbox rounded border-gray-300" value="{{ $fee->fee_ids->implode(',') }}">
                                </td>
                                <td class="px-3 py-2">
                                    <button type="button"
                                            class="expand-toggle inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-500 text-white hover:bg-green-600"
                                            data-fee-ids="{{ $fee->fee_ids->implode(',') }}"
                                            data-creator="{{ $fee->created_by_name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-3 py-2">{{ $i + 1 }}</td>
                                <td class="px-3 py-2">{{ $fee->student->student_id ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    {{ trim(($fee->student->first_name ?? '') . ' ' . ($fee->student->last_name ?? '')) }}
                                </td>
                                <td class="px-3 py-2">{{ $fee->class->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $fee->section->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $fee->invoice_no ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $fee->fiscal_year ?? '-' }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($fee->bill_amount ?? 0, 2) }}</td>
                                <td class="px-3 py-2">{{ $fee->billingPeriod->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $fee->bill_date ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $fee->due_date ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @if ($fee->active ?? true)
                                        <span class="text-green-600 font-medium">true</span>
                                    @else
                                        <span class="text-gray-400">false</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DataTables + Buttons extension (search, sort, Copy/CSV/Excel/PDF/Print, column visibility) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-dt@1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-buttons-dt@2.4.2/css/buttons.dataTables.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2.4.2/js/buttons.colVis.min.js"></script>

    <script>
        window.addEventListener('error', function (e) {
            if (e.target && e.target.tagName === 'SCRIPT') {
                console.error('Script failed to load:', e.target.src);
            }
        }, true);
    </script>

    <script>
        $(function () {
            const table = $('#assignedFeeTable').DataTable({
                columnDefs: [{ orderable: false, targets: [0, 1] }],
                lengthMenu: [10, 20, 50, 100],
                pageLength: 20,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copyHtml5', text: 'Copy', exportOptions: { columns: ':visible:not(.no-export)' } },
                    { extend: 'csvHtml5', text: 'CSV', exportOptions: { columns: ':visible:not(.no-export)' } },
                    { extend: 'excelHtml5', text: 'Excel', exportOptions: { columns: ':visible:not(.no-export)' } },
                    { extend: 'pdfHtml5', text: 'PDF', exportOptions: { columns: ':visible:not(.no-export)' } },
                    { extend: 'colvis', text: 'Column visibility' },
                    { extend: 'print', text: 'Print', exportOptions: { columns: ':visible:not(.no-export)' } },
                ],
            });

            // child-row expand/collapse: shows who assigned the fee + Print/View/Delete actions
            const plusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>';
            const minusIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>';

            function childRowHtml(feeIds, creator) {
                const invoiceUrl = "{{ route('school-admin.fee-assign.invoice') }}?ids=" + encodeURIComponent(feeIds);
                return `
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3">
                        <span class="text-sm text-gray-600">User Name: <strong class="text-gray-800">${creator}</strong></span>
                        <div class="flex items-center gap-2">
                            <a href="${invoiceUrl}&print=1" target="_blank" title="Print"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-sky-100 text-sky-600 hover:bg-sky-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
                                </svg>
                            </a>
                            <a href="${invoiceUrl}" target="_blank" title="View"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <button type="button" class="delete-invoice-btn inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200"
                                    data-fee-ids="${feeIds}" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            }

            $('#assignedFeeTable tbody').on('click', '.expand-toggle', function () {
                const btn = $(this);
                const tr = btn.closest('tr');
                const row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    btn.html(plusIcon).removeClass('bg-red-500 hover:bg-red-600').addClass('bg-green-500 hover:bg-green-600');
                } else {
                    row.child(childRowHtml(btn.data('fee-ids'), btn.data('creator'))).show();
                    tr.addClass('shown');
                    btn.html(minusIcon).removeClass('bg-green-500 hover:bg-green-600').addClass('bg-red-500 hover:bg-red-600');
                }
            });

            // delete (void) a single invoice group from the expand row
            $('#assignedFeeTable').on('click', '.delete-invoice-btn', function () {
                const feeIds = $(this).data('fee-ids');
                if (!confirm('Delete this fee assignment? This cannot be undone.')) return;

                fetch("{{ route('school-admin.fee-assign.bulk-void') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ ids: [String(feeIds)] }),
                }).then(res => {
                    if (res.ok) location.reload();
                    else alert('Failed to delete this record.');
                });
            });

            // select all rows
            $('#selectAllRows').on('change', function () {
                $('.row-checkbox').prop('checked', this.checked);
            });

            // bulk void — collect checked ids and post to your void endpoint
            $('#bulkVoidBtn').on('click', function () {
                const ids = $('.row-checkbox:checked').map(function () { return this.value; }).get();
                if (ids.length === 0) {
                    alert('Please select at least one row to void.');
                    return;
                }
                if (!confirm('Void ' + ids.length + ' selected fee assignment(s)?')) return;

                // TODO: point this to your actual bulk-void route
                fetch("{{ route('school-admin.fee-assign.bulk-void') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ ids }),
                }).then(res => {
                    if (res.ok) location.reload();
                    else alert('Failed to void selected records.');
                });
            });

            // invoice — collect checked ids and open invoice route
            $('#invoiceBtn').on('click', function () {
                const ids = $('.row-checkbox:checked').map(function () { return this.value; }).get();
                if (ids.length === 0) {
                    alert('Please select at least one row to print invoice.');
                    return;
                }
                // TODO: point this to your actual invoice route
                window.open("{{ route('school-admin.fee-assign.invoice') }}?ids=" + ids.join(','), '_blank');
            });
        });
    </script>
</x-app-layout>