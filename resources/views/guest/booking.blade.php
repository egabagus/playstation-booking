@extends('livewire.layout.guest-layout')

@section('content')
    <h2>Booking Kalender</h2>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/5">
            <h2 class="text-xl font-semibold mb-4">Form Booking</h2>

            <!-- Form -->
            <form id="bookingForm">
                <input type="hidden" id="resource_id" name="resource_id">

                <div class="mb-3">
                    <label class="block text-gray-700">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label class="block text-gray-700">Dari Jam</label>
                    <input type="time" name="start_time" id="start_time" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label class="block text-gray-700">Sampai Jam</label>
                    <input type="time" name="end_time" id="end_time" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="calendar"></div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');

                $.get('/product', function(resources) {
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        selectable: true,
                        initialView: 'resourceTimelineWeek',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'resourceTimelineWeek,timeGridDay'
                        },
                        resources: resources,
                        select: function(info) {
                            openModal(info); 
                        }
                    });

                    calendar.render();
                });
            });

            function openModal(info) {
                document.getElementById('modal').classList.remove('hidden');

                // Isi input tanggal dengan data yang diklik
                document.getElementById('tanggal').value = info.startStr.split('T')[0];
                const timeStart = info.startStr.split('T')[1].split(':').slice(0, 2).join(':');
                document.getElementById('start_time').value = timeStart;

                const timeEnd = info.endStr.split('T')[1].split(':').slice(0, 2).join(':');
                document.getElementById('end_time').value = timeEnd;
                document.getElementById('resource_id').value = info.resource._resource.id;
            }

            function closeModal() {
                document.getElementById('modal').classList.add('hidden');
            }

            document.getElementById('modal').addEventListener('click', function(event) {
                event.stopPropagation();
            });


            document.getElementById('bookingForm').addEventListener('submit', function(event) {
                event.preventDefault();
                alert('Booked');
                closeModal();
            });
        </script>
    @endpush
@endsection