/**
 * Setting for Maps (Main Dashboard) are defined here
 */

import colors from './../colors';

export default {

    // General Options
    DiscRemainingRefusal: {
        name: 'Refusal',
        title: 'Remaining Refusal',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },
    DiscRemainingAbsent: {
        name: 'Absent',
        title: 'Remaining Absent',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },
    DiscRemainingNSS: {
        name: 'New Born/Sick/Sleep',
        title: 'Remaining NSS',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },
    DiscRemaining: {
        name: 'Total Remaining',
        title: 'Total Remaining',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },

    // Percentage Benchmark
    PerRefusal: {
        name: '% Refusal',
        title: '% of Remaining Refusal',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },
    PerAbsent: {
        name: '% Absent',
        title: '% of Remaining Absent',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },
    PerNSS: {
        name: '% New Born/Sick/Sleep',
        title: '% of Remaining NSS',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },
    PerRemaining: {
        name: '% Total Remaining',
        title: '% of Total Remaining',
        colors: [colors.GREEN, colors.LIGHT_BLUE, colors.YELLOW, colors.ORANGE, colors.RED]
    },

    // these keys should be same as the indicators from database
    region: {
        DiscRemainingRefusal: {
            classes: [
                {to: 150},
                {from: 150, to: 300},
                {from: 300, to: 450},
                {from: 450, to: 600},
                {from: 600}
            ]
        },

        DiscRemainingAbsent: {
            classes: [
                {to: 250},
                {from: 250, to: 500},
                {from: 500, to: 750},
                {from: 750, to: 1000},
                {from: 1000}
            ]
        },

        DiscRemainingNSS: {
            classes: [
                {to: 100},
                {from: 100, to: 200},
                {from: 200, to: 300},
                {from: 300, to: 4000},
                {from: 400}
            ]
        },

        DiscRemaining: {
            classes: [
                {to: 300},
                {from: 300, to: 600},
                {from: 600, to: 900},
                {from: 900, to: 1200},
                {from: 1200}
            ]

        },

        PerRefusal: {
            classes: [
                {to: 0},
                {from: 0, to: 0.25},
                {from: 0.25, to: 0.50},
                {from: 0.50, to: 0.75},
                {from: 0.75}
            ]
        },

        PerAbsent: {
            classes: [
                {to: 0.50},
                {from: 0.50, to: 1},
                {from: 1, to: 1.5},
                {from: 1.5, to: 2},
                {from: 2}
            ]
        },

        PerNSS: {
            classes: [
                {to: 0},
                {from: 0, to: 0.25},
                {from: 0.25, to: 0.50},
                {from: 0.50, to: 0.75},
                {from: 0.75}
            ]
        },

        PerRemaining: {
            classes: [
                {to: 0.50},
                {from: 0.50, to: 1},
                {from: 1, to: 1.5},
                {from: 1.5, to: 2},
                {from: 2}
            ]

        },

    },

    province: {
        DiscRemainingRefusal: {
            classes: [
                {to: 75},
                {from: 75, to: 150},
                {from: 150, to: 225},
                {from: 225, to: 300},
                {from: 300}
            ]
        },

        DiscRemainingAbsent: {
            classes: [
                {to: 125},
                {from: 125, to: 250},
                {from: 250, to: 375},
                {from: 500, to: 625},
                {from: 625}
            ]
        },

        DiscRemainingNSS: {
            classes: [
                {to: 50},
                {from: 50, to: 100},
                {from: 100, to: 150},
                {from: 150, to: 200},
                {from: 200}
            ]
        },

        DiscRemaining: {
            classes: [
                {to: 150},
                {from: 150, to: 300},
                {from: 300, to: 450},
                {from: 450, to: 600},
                {from: 600}
            ]

        },

        PerRefusal: {
            classes: [
                {to: 0},
                {from: 0, to: 0.25},
                {from: 0.25, to: 0.50},
                {from: 0.50, to: 0.75},
                {from: 0.75}
            ]
        },

        PerAbsent: {
            classes: [
                {to: 0.50},
                {from: 0.50, to: 1},
                {from: 1, to: 1.5},
                {from: 1.5, to: 2},
                {from: 2}
            ]
        },

        PerNSS: {
            classes: [
                {to: 0},
                {from: 0, to: 0.25},
                {from: 0.25, to: 0.50},
                {from: 0.50, to: 0.75},
                {from: 0.75}
            ]
        },

        PerRemaining: {
            classes: [
                {to: 0.50},
                {from: 0.50, to: 1},
                {from: 1, to: 1.5},
                {from: 1.5, to: 2},
                {from: 2}
            ]

        },
    },

    district: {
        DiscRemainingRefusal: {
            classes: [
                {to: 50},
                {from: 50, to: 100},
                {from: 100, to: 150},
                {from: 150, to: 200},
                {from: 200}
            ]
        },

        DiscRemainingAbsent: {
            classes: [
                {to: 100},
                {from: 100, to: 200},
                {from: 200, to: 300},
                {from: 300, to: 400},
                {from: 400}
            ]
        },

        DiscRemainingNSS: {
            classes: [
                {to: 30},
                {from: 30, to: 60},
                {from: 60, to: 90},
                {from: 90, to: 120},
                {from: 120}
            ]
        },

        DiscRemaining: {
            classes: [
                {to: 125},
                {from: 125, to: 250},
                {from: 250, to: 375},
                {from: 375, to: 500},
                {from: 500}
            ]

        },

        PerRefusal: {
            classes: [
                {to: 0},
                {from: 0, to: 0.25},
                {from: 0.25, to: 0.50},
                {from: 0.50, to: 0.75},
                {from: 0.75}
            ]
        },

        PerAbsent: {
            classes: [
                {to: 0.50},
                {from: 0.50, to: 1},
                {from: 1, to: 1.5},
                {from: 1.5, to: 2},
                {from: 2}
            ]
        },

        PerNSS: {
            classes: [
                {to: 0},
                {from: 0, to: 0.25},
                {from: 0.25, to: 0.50},
                {from: 0.50, to: 0.75},
                {from: 0.75}
            ]
        },

        PerRemaining: {
            classes: [
                {to: 0.50},
                {from: 0.50, to: 1},
                {from: 1, to: 1.5},
                {from: 1.5, to: 2},
                {from: 2}
            ]

        },
    }

}