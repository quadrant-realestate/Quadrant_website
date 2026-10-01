import {defineCliConfig} from 'sanity/cli'
import {projectId, dataset} from './env'

export default defineCliConfig({
  api: {projectId, dataset},
  studioHost: 'quadrant-blogs',
  deployment: {appId: 'okwplgv2hwc1wyqoll27l6cq', autoUpdates: true},
})
