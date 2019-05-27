package main

import (
	"./jinya"
	"github.com/joho/godotenv"
	"github.com/urfave/cli"
	"log"
	"os"
)

func main() {
	err := godotenv.Load()
	if err != nil {
		log.Fatal(err)
	}

	app := cli.NewApp()
	app.Flags = []cli.Flag{
		cli.StringFlag{
			Name:  "mode, m",
			Usage: "Only create the version file for this version mode, either `stable`, `edge` or `nightly`",
		},
	}
	app.Version = "2.2.0"
	app.Name = "Jinya Files"
	app.Usage = "This application creates the needed files for the Jinya Gallery CMS to run updates"
	app.Action = func(c *cli.Context) {
		jinya.Update(c.String("mode"))
	}

	err = app.Run(os.Args)
	if err != nil {
		log.Fatal(err)
	}
}
