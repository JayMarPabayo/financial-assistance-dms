<x-layout>
    <div class="w-full h-96 mb-10">
        <img src="{{ asset('images/banner.png') }}" alt="Banner" class="object-contain object-top w-full h-full">
    </div>
    <div class="mb-5">
        <h1>In partnership with:</h1>
    </div>
    <section class="flex items-center justify-between mb-5 pb-5 border-b border-sky-900/20">
        <img src="{{ asset('images/CONGRESS.png') }}" class="h-32" alt="Congress Logo">
        <img src="{{ asset('images/DOH.png') }}" class="h-32" alt="DOH Logo">
        <img src="{{ asset('images/DSWD.png') }}" class="h-32" alt="DSWD Logo">
    </section>
    <div class="mb-5 flex items-center gap-x-2">
        <h1 class="font-semibold text-lg">Guide</h1>
    </div>
    <div class="w-full h-96 mb-10 flex justify-center relative overflow-hidden carousel">
        <button class="prev absolute left-0 z-10 bg-gray-500 text-white px-2 py-1">❮</button>
        <div class="carousel-inner flex w-full h-full">
            <img src="{{ asset('steps/01.png') }}" alt="Steps 1" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/02.png') }}" alt="Steps 2" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/03.png') }}" alt="Steps 3" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/04.png') }}" alt="Steps 4" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/05.png') }}" alt="Steps 5" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/06.png') }}" alt="Steps 6" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/07.png') }}" alt="Steps 7" class="object-contain object-top h-full w-full">
            <img src="{{ asset('steps/08.png') }}" alt="Steps 8" class="object-contain object-top h-full w-full">
        </div>
        <button class="next absolute right-0 z-10 bg-gray-500 text-white px-2 py-1">❯</button>
    </div>

    <p class="mb-4 text-justify">
        In the Philippines, a nation known for its strong sense of community and resilience, Financial and cash assistance programs are more than a safety net; they are a testament to the country’s commitment to caring for its citizen. These programs are design to provide timely and targeted assistance to those who find themselves on the blink of uncertainty, whether due to sudden unemployment, health crisis, or the economic impacts of natural calamities. (assistance.ph 2024)
    </p>
    <p class="mb-4 text-justify">
        The Extension Office provide the requirement for Financial Assistance. After the requirements it will be submitted to the Department of Social Welfare and Development. The DSWD is responsible for the disbursement of the Financial Aid.
    </p>
    <div class="w-full h-96 mb-10">
        <img src="{{ asset('images/banner-2.jpg') }}" alt="Banner" class="object-cover object-top w-full h-full">
    </div>
    <p class="mb-4 text-justify">
        The Extension Office of Tagoloan stands out as a prominent Financial Assistance organization, catering to individuals in need of support. For Filipinos this assistance initiatives hold propound relevance. The problem may occur due to queueing to multiple individuals applying for the Financial Assistance. Also, dealing with many follow up concerns of clients who applied for the Financial Assistance regarding to their requests. The office staff work load will be double while entertaining other clients. In addition, the staffs personal contact number are being asked by the client to get the notification when is the schedule for the applied assistance they need, it is a burden for the staff even on nonworking hours specially on weekend when some clients called just to get the information of what is the status of their assistance.
    </p>
    <p class="mb-10 text-justify">
        This system will streamline operation and reduce the hassle of individuals who take multiple trips for follow-ups. The system will help to provide the list of assistance services such as Burial Assistance, Calamity Assistance, Educational Assistance, Medical Billing Assistance, Transportation Assistance, Medication Assistance and Solicitation and other serving desired assistance. The client may upload the picture copy of the requirements document to fulfill the process. The staff may then verify the completion of all necessary documents uploaded by the applicant. Upon approval, the client may notify a SMS message to schedule a meeting for the client to submit hard copies documents required at the Extension Office. 
    </p>

</x-layout>

<style>
    .carousel-inner {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }
    
    .carousel img {
        flex-shrink: 0;
        width: 100%;
    }
    
    button.prev, button.next {
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        border: none;
        cursor: pointer;
        z-index: 1;
    }
    
    button.prev:hover, button.next:hover {
        background: rgba(0, 0, 0, 0.8);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.querySelector('.carousel-inner');
        const slides = carousel.children;
        const totalSlides = slides.length;
        let index = 0;
    
        document.querySelector('.next').addEventListener('click', function() {
            index = (index + 1) % totalSlides; // Go to next image or loop to start
            updateCarousel();
        });
    
        document.querySelector('.prev').addEventListener('click', function() {
            index = (index - 1 + totalSlides) % totalSlides; // Go to previous image or loop to end
            updateCarousel();
        });
    
        function updateCarousel() {
            carousel.style.transform = `translateX(-${index * 100}%)`;
        }
    });
</script>
