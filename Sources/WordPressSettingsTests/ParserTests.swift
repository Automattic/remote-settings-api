import Foundation
import Testing
import WordPressSettings

@Test("Test parsing JSON")
func testJsonParser() throws {
    let url = Bundle.module.url(forResource: "test1", withExtension: "json")!
    let json = try Data(contentsOf: url)
    let suite = try JSONDecoder().decode(SettingsSuite.self, from: json)
    debugPrint(suite)
    #expect(suite != nil)
}
