import {defineCliConfig} from 'sanity/cli'
import {projectId, dataset} from './env'

export default defineCliConfig({
  api: {projectId, dataset},
  studioHost: 'quadrant-blog',
  deployment: {appId: 'bw2pd02mi2olbgtdmcc18m2u', autoUpdates: true},
})
