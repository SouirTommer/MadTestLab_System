<script>
    import CtAs from "./CTAs.svelte";
    import Header from "./Header.svelte";
    import SectionWrapper from "./SectionWrapper.svelte";
</script>

<SectionWrapper>
    <Header />
    <div
        class="flex flex-col gap-10 flex-1 items-center justify-center pb-10 md:pb-14"
    >
        <h2
            class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl max-w-[1200px] mx-auto w-full text-center font-semibold"
        >
            <span class="text-indigo-400">MedTest </span>
            <span class="text-slate-600">Lab </span>
            <i class="fa-solid fa-vial"></i>
            <br />
            <br />
            All your medical tests
            <br />
            in <span class="text-indigo-400"> one place</span>
            <br />
            <br />
        </h2>
        <p
            class="text-xl smLtext-2xl md:text-3xl text-center max-w-[1000px] mx-auto w-full"
        >
            We provide a wide range of medical tests and
            <br />
            services to help you take control of your health.
        </p>
        <CtAs />
    </div>
</SectionWrapper>
