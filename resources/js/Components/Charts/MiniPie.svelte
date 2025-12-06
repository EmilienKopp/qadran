<script lang="ts">
    import { onMount } from "svelte";

    export let data: number[];

    // get only the 5 largest numbers
    let numbers = data.sort((a, b) => b - a).slice(0, 4);
    if(numbers.length <= 1) {
        numbers = [Math.random(), Math.random(), Math.random()]
    }

    // Normalize to make their sum 360
    let total = numbers.reduce((a, b) => a + b, 0);
    let normalized = numbers.map(n => (n / total) * 360);

    let strokeWidth = 0.05;
    let extraSpace = strokeWidth / 2;
    let viewBoxSize = 2 + extraSpace * 2;
    let viewBoxMin = -1 - extraSpace;

    // SVG setup
    let svgns = "http://www.w3.org/2000/svg";
    let chart: SVGElement;
    onMount(() => {

        chart.setAttribute("viewBox", `${viewBoxMin} ${viewBoxMin} ${viewBoxSize} ${viewBoxSize}`); // Setting the viewbox

        let currentAngle = 0;

        normalized.forEach(sliceAngle => {
            
            // Calculate the end angle
            let endAngle = currentAngle + sliceAngle;

            // Coordinates for the end point of the slice
            let largeArc = sliceAngle > 180 ? 1 : 0;
            let x = Math.cos((endAngle - 90) * Math.PI / 180);
            let y = Math.sin((endAngle - 90) * Math.PI / 180);

            // Construct the 'd' attribute of the path
            let d = [
                "M 0 0", // Move to the center
                `L ${Math.cos((currentAngle - 90) * Math.PI / 180)} ${Math.sin((currentAngle - 90) * Math.PI / 180)}`, // Line to the start of the slice
                `A 1 1 0 ${largeArc} 1 ${x} ${y}`, // Arc to the end of the slice
                "Z" // Close the path
            ].join(" ");

            // Create the path element
            let path = document.createElementNS(svgns, "path");
            path.setAttribute("d", d);
            path.setAttribute("fill", "#AFAFAF");
            path.setAttribute("stroke", "#131930");
            path.setAttribute("stroke-width", strokeWidth.toString()); 

            // Append the path to the SVG element
            chart.appendChild(path);

            // Update the currentAngle
            currentAngle = endAngle;
        });
    });

</script>

<div class="w-10 h-10 p-2 rounded-md flex items-center justify-between">
    <svg bind:this={chart} class="mx-auto"></svg>
</div>