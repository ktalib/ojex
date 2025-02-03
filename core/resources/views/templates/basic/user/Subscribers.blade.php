@extends($activeTemplate . 'layouts.master2')
@php
        $kyc = getContent('kyc.content', true);
    @endphp

<style>
     
    .tabs-container {
      width: 100%;
      max-width: 400px;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .tabs-header {
      display: flex;
      border-bottom: 2px solid #e0e0e0;
    }

    .tab-button {
      flex: 1;
      padding: 10px 15px;
      text-align: center;
      cursor: pointer;
      font-weight: bold;
      color: #555;
      border: none;
      outline: none;
      background: none;
      transition: color 0.3s, background-color 0.3s;
    }

    .tab-button.active {
      color: #ffffff;
      background: #26292c;
    }

    .tabs-content {
      padding: 20px;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }
  </style>
@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
        <!-- Example cards -->



        <div class="p-4 bg-black rounded-lg shadow">

            <div>


                <div class="grid justify-items-stretch">
                    <div>
                        <h3 class="text-sm text-gray-400">Total Balance</h3>
                        <p class="text-2xl font-semibold">{{ showAmount(auth()->user()->balance) }}
                        </p>
                    </div>


                </div>

                <div class="grid justify-items-end">
                    <div>
                        <button class="p-2 hover:bg-gray-800 rounded-lg tab-button active" data-tab="tab1" >
                            <i class="ri-file-list-line text-gray-400"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-800 rounded-lg tab-button" data-tab="tab2">
                            <i class="ri-smartphone-line text-gray-400"></i>
                        </button>
                    </div>
                </div>
  
 


 
            </div>
        </div>


       

        
        
 
    </div>
    <div class="p-1 space-y-4">

        <div class="rounded-lg border border-gray-800 bg-black p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
   
            </div> 
        </div>
        </div>
    </main>
    <script>
 
 
   </script>
@endsection
