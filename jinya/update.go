package jinya

import (
	"../environment"
	"../types"
	"../utils"
	"encoding/json"
	"io/ioutil"
	"log"
	"os"
	"path"
	"path/filepath"
	"strings"
)

func Update(mode string) {
	validModes := []string{"edge", "nightly", "stable"}
	filesBaseDir := environment.GetFilesBaseDir()
	filesBaseUrl := environment.GetFilesBaseUrl()

	if !utils.Contains(validModes, mode) {
		for _, mode := range validModes {
			Update(mode)
		}
	} else {
		files, err := scanForZips(filesBaseDir, mode)

		if err != nil {
			log.Fatal(err)
		} else {
			versionDump := getVersionDump(filesBaseUrl, mode, files)
			versionsFile := path.Join(filesBaseDir, mode+".json")

			log.Printf("Dump version list to file %s", versionsFile)
			versionsJson, err := json.Marshal(versionDump)

			if err != nil {
				log.Fatal(err)
			} else {
				ioutil.WriteFile(versionsFile, versionsJson, 0777)
			}
		}
	}
}

func getVersionDump(filesBaseUrl string, mode string, files []os.FileInfo) *types.VersionDump {
	log.Printf("Create version dump")

	versionDump := new(types.VersionDump)
	versionDump.Cms = map[string]string{}
	jinyaBaseUrl := filesBaseUrl + "cms/" + mode

	log.Printf("Use %s as base url for files", jinyaBaseUrl)
	for _, file := range files {
		version := strings.TrimSuffix(file.Name(), filepath.Ext(file.Name()))
		fileUrl := jinyaBaseUrl + "/" + file.Name()
		versionDump.Cms[version] = fileUrl
		log.Printf("Added version %s under url %s", version, fileUrl)
	}

	return versionDump
}

func scanForZips(filesBaseDir string, mode string) ([]os.FileInfo, error) {
	jinyaDir := path.Join(filesBaseDir, "cms", mode)
	log.Printf("Using directory %s for scan", jinyaDir)

	files, err := ioutil.ReadDir(jinyaDir)
	if err != nil {
		log.Fatal(err)
		return nil, err
	}

	log.Printf("Filter %d files for zip files", len(files))
	zipFiles := make([]os.FileInfo, 0)
	for _, file := range files {
		if !file.IsDir() && filepath.Ext(file.Name()) == ".zip" {
			zipFiles = append(zipFiles, file)
		}
	}

	log.Printf("Found %d files in directory %s", len(zipFiles), jinyaDir)

	return zipFiles, nil
}
