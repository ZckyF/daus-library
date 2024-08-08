@push('styles')
    <style lang="scss">
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    .selected-year {
        cursor: pointer;
    }
    </style>
@endpush


<div class="mt-5 ">
  <div class="row g-2">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalUsers }}" icon="person-fill" >Users</x-cards.dashboard-card> 
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalMembers }}" icon="person-vcard-fill" >Members</x-cards.dashboard-card> 
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalBooks }}" icon="book-fill" >Books</x-cards.dashboard-card> 
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalBookCategories }}" icon="tags-fill" >Categories</x-cards.dashboard-card> 
    </div>
  </div>
  <div class="row mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Chart Borrow Book</h3>
      <select wire:model.change="selectedYear" class="selected-year form-select w-auto border-0 shadow-sm">
          @foreach($years as $year)
              <option class="border-0" value="{{ $year }}">{{ $year }}</option>
          @endforeach
      </select>
    </div>
    <canvas id="borrowBookChart" class="bg-white rounded-3 shadow"></canvas>
    {{-- <div class="position-relative">
        <canvas id="borrowBookChart" class="bg-white rounded-3 shadow"></canvas>
        <div class="position-absolute top-50 start-50 translate-middle" wire:loading wire:target="selectedYear" style="display: none">
            <div class="spinner-border text-primary" role="status"></div>
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> --}}
  </div>
  <div class="row mt-5">
    
    <div class="col-lg-6">
        <h3 class="mb-4">Top 10 borrowed the most books</h3>
        <x-tables.table tableClass="table-striped" :columns="['Rank', 'Full Name', 'Borrow Count']"> 
            @foreach($topMembers as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $member->member->full_name }}</td>
                    <td>{{ $member->borrow_count }}</td>
                </tr>
            @endforeach
        </x-tables.table>
    </div>
    <div class="col-lg-6">
        <h3 class="mb-4">Top 10 most borrowed books</h3>    
        <x-tables.table tableClass="table-striped" :columns="['Rank', 'Title', 'Borrow Count']">
            <tbody>
                @foreach($topBooks as $index => $book)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ Str::limit($book->title, 40) }}</td>
                        <td>{{ $book->borrow_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </x-tables.table>
    </div>
  </div>
</div>




@push('scripts')
    @assets
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets
    <script>
        $(document).ready(function() {
            const ctx = $('#borrowBookChart');
            let chart;
    
            function renderChart(data) {
                if (chart) {
                    chart.destroy();
                }
    
                const datasets = [];
                const colors = {
                    borrowed: 'rgba(147, 112, 219, 0.2)',
                    returned: 'rgba(75, 192, 192, 0.2)',
                    lost: 'rgba(255, 99, 132, 0.2)',
                    damaged: 'rgba(255, 206, 86, 0.2)',
                    due: 'rgba(255, 192, 203, 0.2)'
                };
    
                Object.keys(data).forEach(status => {
                    datasets.push({
                        label: status.charAt(0).toUpperCase() + status.slice(1),
                        data: Object.values(data[status]),
                        backgroundColor: colors[status],
                        borderColor: colors[status].replace('0.2', '1'),
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: colors[status].replace('0.2', '1'),
                        pointBorderColor: colors[status].replace('0.2', '1'),
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    });
                });
    
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    },
                                    stepSize: 1 
                                }
                            }
                        }
                    }

                });
            }
    
            window.addEventListener('updateChart', function(event) {
                renderChart(event.detail[0].data);
            });
    
            renderChart(@json($borrowData));
        });
    </script>
    
@endpush

