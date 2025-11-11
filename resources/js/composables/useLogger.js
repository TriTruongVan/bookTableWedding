/* eslint-disable no-console */
export const useLogger = () => {
  const logger = {
    log: (...messages) => {
      console.log('[INFO]', ...messages)
    },
    error: (...messages) => {
      console.log('[ERROR]', ...messages)
    }
  }
  return {
    logger
  }
}
