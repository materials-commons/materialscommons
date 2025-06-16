// Tour service for Materials Commons
// This service manages tours and tracks state across pages

// Import Shepherd.js
import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';
import axios from 'axios';

// Available tours
const TOURS = {
  dashboard: {
    name: 'dashboard',
    steps: [
      {
        id: 'dashboard-welcome',
        title: 'Welcome to the Dashboard',
        text: 'The dashboard search bar allows you to search across for projects, and across projects for samples, computations, datasets, and files.',
        attachTo: {
          element: '#navbar-search-input',
          on: 'bottom'
        },
        buttons: [
          {
            text: 'Next',
            action: function() {
              return this.next();
            }
          }
        ]
      },
      {
        id: 'dashboard-projects',
        title: 'Projects',
        text: 'The projects tab shows all of the projects you have access to.',
        attachTo: {
          element: '#dashboard-projects-tab',
          on: 'bottom'
        },
        buttons: [
          {
            text: 'Next',
            action: function() {
              return this.next();
            }
          }
        ]
      }
    ]
  },
  project: {
    name: 'project',
    steps: []
  },
  publishing: {
    name: 'publishing',
    steps: []
  },
  etl: {
    name: 'etl',
    steps: []
  }
};

// Tour service
class TourService {
  constructor() {
    this.shepherd = null;
    this.currentTour = null;
    this.apiToken = null;
    this.state = {
      completedSteps: {},
      completedTours: {}
    };

    // Initialize state asynchronously
    // this.initState();
  }

  // Initialize state from database
  async initState(apiToken) {
    this.apiToken = apiToken;
    this.state = await this.loadState();
  }

  // Load tour state from database
  async loadState() {
    try {
      const response = await axios.get(`/api/tour-states?api_token=${this.apiToken}`);
      return {
        completedSteps: response.data.completedSteps || {},
        completedTours: response.data.completedTours || {}
      };
    } catch (error) {
      console.error('Error loading tour state:', error);
      return {
        completedSteps: {},
        completedTours: {}
      };
    }
  }

  // Save tour state to database
  async saveState() {
    try {
      // This is a placeholder as we'll use specific API endpoints for each action
      console.log('Tour state saved via API');
    } catch (error) {
      console.error('Error saving tour state:', error);
    }
  }

  // Mark a step as completed
  async completeStep(tourName, stepId) {
    try {
      // Update local state first for immediate feedback
      if (!this.state.completedSteps[tourName]) {
        this.state.completedSteps[tourName] = [];
      }

      if (!this.state.completedSteps[tourName].includes(stepId)) {
        this.state.completedSteps[tourName].push(stepId);
      }

      // Save to database
      await axios.post(`/api/tour-states/complete-step?api_token=${this.apiToken}`, {
        tourName: tourName,
        stepId: stepId
      });
    } catch (error) {
      console.error('Error completing step:', error);
    }
  }

  // Check if a step is completed
  isStepCompleted(tourName, stepId) {
    return this.state.completedSteps[tourName] && 
           this.state.completedSteps[tourName].includes(stepId);
  }

  // Mark a tour as completed
  async completeTour(tourName) {
    try {
      // Update local state first for immediate feedback
      this.state.completedTours[tourName] = true;

      // Save to database
      await axios.post(`/api/tour-states/complete-tour?api_token=${this.apiToken}`, {
        tourName: tourName
      });
    } catch (error) {
      console.error('Error completing tour:', error);
    }
  }

  // Check if a tour is completed
  isTourCompleted(tourName) {
    return !!this.state.completedTours[tourName];
  }

  // Reset a specific tour
  async resetTour(tourName) {
    try {
      // Update local state first for immediate feedback
      if (this.state.completedSteps[tourName]) {
        delete this.state.completedSteps[tourName];
      }

      if (this.state.completedTours[tourName]) {
        delete this.state.completedTours[tourName];
      }

      // Save to database
      await axios.post(`/api/tour-states/reset?api_token=${this.apiToken}`, {
        tourName: tourName
      });
    } catch (error) {
      console.error('Error resetting tour:', error);
    }
  }

  // Reset all tours
  async resetAllTours() {
    try {
      // Update local state first for immediate feedback
      this.state = {
        completedSteps: {},
        completedTours: {}
      };

      // Save to database
      await axios.post(`/api/tour-states/reset-all?api_token=${this.apiToken}`);
    } catch (error) {
      console.error('Error resetting all tours:', error);
    }
  }

  // Start a tour
  startTour(tourName) {
    if (!TOURS[tourName]) {
      console.error(`Tour "${tourName}" not found`);
      return;
    }

    this.currentTour = tourName;

    // Initialize Shepherd
    this.shepherd = new Shepherd.Tour({
      defaultStepOptions: {
        cancelIcon: {
          enabled: true
        },
        classes: 'shepherd-theme-default',
        scrollTo: false
      }
    });

    // Add steps to the tour
    const tour = TOURS[tourName];
    tour.steps.forEach(step => {
      this.shepherd.addStep({
        id: step.id,
        title: step.title,
        text: step.text,
        attachTo: step.attachTo,
        buttons: step.buttons,
        beforeShowPromise: () => {
          return new Promise(async (resolve) => {
            try {
              // Mark step as completed when shown
              await this.completeStep(tourName, step.id);
              resolve();
            } catch (error) {
              console.error('Error in beforeShowPromise:', error);
              resolve(); // Resolve anyway to show the step
            }
          });
        }
      });
    });

    // Start the tour
    this.shepherd.start();

    // When tour is complete
    this.shepherd.on('complete', async () => {
      try {
        await this.completeTour(tourName);
      } catch (error) {
        console.error('Error completing tour:', error);
      }
    });
  }

  // Get the appropriate tour for the current route
  getTourForRoute(route) {
    if (route.includes('dashboard')) {
      return 'dashboard';
    } else if (route.includes('projects.show')) {
      return 'project';
    } else if (route.includes('publishing')) {
      return 'publishing';
    } else if (route.includes('etl')) {
      return 'etl';
    }
    return null;
  }
}

// Create and export the tour service instance
const tourService = new TourService();
export default tourService;
