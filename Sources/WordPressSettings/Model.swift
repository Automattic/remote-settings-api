import Foundation


public struct SettingsSuite: Codable, Identifiable {
    public var id: String {
        self.title
    }

    let title: String
    let icon: String?
    let color: String?

    let pages: [SettingPage]
    let groups: [SettingGroup]
    let settings: [Setting]
}

struct SettingPage: Codable, Identifiable {
    public var id: String {
        self.title
    }

    let title: String
    let description: String
    let settings: [Setting]
    let groups: [SettingGroup]
}

struct SettingGroup: Codable, Identifiable {
    public var id: String {
        self.title
    }

    let title: String
    let headerText: String?
    let footerText: String?
    let settings: [Setting]
}

struct Setting: Codable, Identifiable {

    enum SettingValue: Codable {
        case bool(Bool)
        case string(String)
        case color(String)
        case date(Date)
        case set // A set of allowed values
        case uuid(UUID)
        case postId(Int)
        case mediaId(Int)
        case userId(Int)
        case role(String)
    }

    let type: String

    let key: String

    let value: SettingValue

    let description: String?

    let allowedValues: [String]?

    init(from decoder: any Decoder) throws {
        let container = try decoder.container(keyedBy: CodingKeys.self)
        self.type = try container.decode(String.self, forKey: .type)
        self.key = try container.decode(String.self, forKey: .key)
        self.description = try container.decodeIfPresent(String.self, forKey: .description)
        self.allowedValues = try container.decodeIfPresent([String].self, forKey: .allowedValues)

        switch type {
            case "bool": self.value = try .bool(container.decode(Bool.self, forKey: .value))
            case "string": self.value = try .string(container.decode(String.self, forKey: .value))
            case "color": self.value = try .string(container.decode(String.self, forKey: .value))
            default: throw CocoaError(.coderInvalidValue)
        }
    }

    init (type: String, key: String, value: SettingValue, description: String? = nil, allowedValues: [String]? = nil) {
        self.type = type
        self.key = key
        self.value = value
        self.description = description
        self.allowedValues = allowedValues
    }

    var id: String {
        self.key
    }
}
