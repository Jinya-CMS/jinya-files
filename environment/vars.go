package environment

import "os"

func GetFilesBaseDir() string {
	return os.Getenv("FILES_BASE_DIR")
}

func GetFilesBaseUrl() string {
	return os.Getenv("FILES_BASE_URL")
}
