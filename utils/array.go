package utils

func Contains(array []string, item string) bool {
	for _, a := range array {
		if a == item {
			return true
		}
	}

	return false
}
